<?php
const API_TOKEN = "53b79f65e6814a6294731dd0f75b1683";

require_once __DIR__ . '/../public/login/connect.php';
require_once __DIR__ . '/../public/login/connect.php';


function callApi($url) {

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "X-Auth-Token: " . API_TOKEN
        ],
        CURLOPT_TIMEOUT => 10
    ]);

    $response = curl_exec($curl);

    if ($response === false) {
        $error = curl_error($curl);
        curl_close($curl);
        throw new Exception("Erreur API : " . $error);
    }

    curl_close($curl);

    $data = json_decode($response, true);

    if (!isset($data['matches'])) {
        throw new Exception("Réponse API invalide");
    }

    return $data['matches'];
}


function getClubIdByApiName($pdo, $apiName) {
    $stmt = $pdo->prepare("SELECT id FROM club WHERE api_name = ?");
    $stmt->execute([$apiName]);
    return $stmt->fetchColumn() ?: null;
}


function getMatchsByDate($pdo, $champ, $dateBegin, $dateEnd) {

    $url = "https://api.football-data.org/v4/competitions/$champ/matches?dateFrom=$dateBegin&dateTo=$dateEnd";

    $apiMatches = callApi($url);

    $matchs = [];

    foreach ($apiMatches as $match) {

        $dateTime = new DateTime($match['utcDate']);

        $matchs[] = [
            "club1" => getClubIdByApiName($pdo, $match['homeTeam']['name']),
            "club2" => getClubIdByApiName($pdo, $match['awayTeam']['name']),
            "date"  => $dateTime->format('Y-m-d'),
            "hour"  => $dateTime->format('H:i:s'),
            "score1" => $match['score']['fullTime']['home'] ?? null,
            "score2" => $match['score']['fullTime']['away'] ?? null
        ];
    }

    return $matchs;
}


function majScoreMatch($pdo, $id, $score1, $score2) {
    $stmt = $pdo->prepare("
        UPDATE matchs
        SET score1 = ?, score2 = ?
        WHERE id = ?
    ");
    $stmt->execute([$score1, $score2, $id]);
}


function getScore($pdo, $matchs, $champ) {

    if (empty($matchs)) return [];

    $dates = array_column($matchs, 'date');
    $dateMin = min($dates);
    $dateMax = max($dates);

    $url = "https://api.football-data.org/v4/competitions/$champ/matches?dateFrom=$dateMin&dateTo=$dateMax";

    $apiMatches = callApi($url);

    $clubsMap = [];
    foreach ($matchs as $m) {
        $clubsMap[$m['club1'] . '|' . $m['club2']] = true;
    }

    $scores = [];

    foreach ($apiMatches as $match) {

        $home = $match['homeTeam']['name'];
        $away = $match['awayTeam']['name'];

        if (
            isset($clubsMap[$home . '|' . $away]) ||
            isset($clubsMap[$away . '|' . $home])
        ) {

            $stmt = $pdo->prepare("
                SELECT id FROM matchs
                WHERE club1 = (SELECT id FROM club WHERE api_name = ?)
                AND club2 = (SELECT id FROM club WHERE api_name = ?)
            ");

            $stmt->execute([$home, $away]);
            $id = $stmt->fetchColumn();

            $scores[] = [
                "id" => $id,
                "score_home" => $match['score']['fullTime']['home'] ?? null,
                "score_away" => $match['score']['fullTime']['away'] ?? null
            ];
        }
    }

    return $scores;
}


function getPeriodesSansMatch($pdo, $competition) {

    $stmt = $pdo->prepare("
        SELECT dc.*
        FROM day_compet dc
        WHERE dc.competition = ?
        AND dc.date_end > DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
        AND NOT EXISTS (
            SELECT 1
            FROM matchs m
            WHERE m.competition = dc.competition
              AND m.date_match >= dc.date_begin
              AND m.date_match <= dc.date_end
        )
    ");

    $stmt->execute([$competition]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function syncMatchs($pdo, $competition, $periodes) {

    $insert = $pdo->prepare("
        INSERT INTO matchs (club1, club2, date_match, hour_match, score1, score2, competition)
        VALUES (:club1, :club2, :date_match, :hour_match, :score1, :score2, :competition)
    ");

    foreach ($periodes as $period) {

        $start = min($period['date_begin'], $period['date_end']);
        $end   = max($period['date_begin'], $period['date_end']);

        try {

            $matchs = getMatchsByDate($pdo, $competition, $start, $end);

            foreach ($matchs as $match) {

                if (!$match['club1'] || !$match['club2']) continue;

                $insert->execute([
                    'club1' => $match['club1'],
                    'club2' => $match['club2'],
                    'date_match' => $match['date'],
                    'hour_match' => $match['hour'],
                    'score1' => null,
                    'score2' => null,
                    'competition' => $competition,
                ]);
            }

        } catch (Exception $e) {
            echo "Erreur sync : " . $e->getMessage();
        }
    }

    return true;
}

$periodesFr = getPeriodesSansMatch($pdo, 'FL1');
if (!empty($periodesFr)) {
    syncMatchs($pdo, $competition = 'FL1', $periodesFr);
}

$periodesEs = getPeriodesSansMatch($pdo, 'PD');
if (!empty($periodesEs)) {
    syncMatchs($pdo, $competition = 'PD', $periodesEs);
}