<?php
function getScore($matchs, $champ) {
    $apiToken = "53b79f65e6814a6294731dd0f75b1683";
    $scores = [];

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=footdb;charset=utf8", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        return ["error" => "Erreur PDO : " . $e->getMessage()];
    }

    if (empty($matchs)) {
        return $scores;
    }

    $dateMax = $matchs[0]['date'];
    $dateMin = $matchs[0]['date'];

    foreach($matchs as $match) {
        if($match['date'] > $dateMax) $dateMax = $match['date'];
        if($match['date'] < $dateMin) $dateMin = $match['date'];
    }

    $url = "https://api.football-data.org/v4/competitions/$champ/matches?dateFrom=$dateMin&dateTo=$dateMax";

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "X-Auth-Token: $apiToken"
        ],
    ]);

    $response = curl_exec($curl);

    if ($response === false) {
        $error = curl_error($curl);
        curl_close($curl);
        return ["error" => $error];
    }

    curl_close($curl);

    $data = json_decode($response, true);

    if (!isset($data['matches'])) {
        return [];
    }
    $clubsMap = [];
    foreach ($matchs as $m) {
        $clubsMap[$m['club1'] . '|' . $m['club2']] = true;
    }

    foreach ($data['matches'] as $match) {
        $home = $match['homeTeam']['name'];
        $away = $match['awayTeam']['name'];
        $scoreHome = $match['score']['fullTime']['home'];
        $scoreAway = $match['score']['fullTime']['away'];



        if (isset($clubsMap[$home . '|' . $away]) || isset($clubsMap[$away . '|' . $home])) {
            $stmt = $pdo->prepare("
                SELECT id FROM matchs
                WHERE club1 = (SELECT id FROM club WHERE api_name = ?)
                  AND club2 = (SELECT id FROM club WHERE api_name = ?)
                ");
            $stmt->execute([$match['homeTeam']['name'], $match['awayTeam']['name']]);
            $id = $stmt->fetchColumn();

            $scores[] = [
                "id" => $id,
                "home_team"  => $home,
                "away_team"  => $away,
                "date_time"  => $match['utcDate'],
                "score_home" => $scoreHome,
                "score_away" => $scoreAway
            ];
        }
    }

    return $scores;
}




function majScoreMatch($pdo, $id, $score1, $score2) {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare("
        UPDATE matchs
        SET score1 = ?, score2 = ?
        WHERE id = ?
    ");
    $stmt->execute([$score1, $score2, $id]);
}