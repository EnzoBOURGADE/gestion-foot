<?php
function getScoreByMatch($date, $club1, $club2, $champ) {

    $apiToken = "53b79f65e6814a6294731dd0f75b1683";

    $url = "https://api.football-data.org/v4/competitions/$champ/matches?dateFrom=$date&dateTo=$date";

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
        curl_close($curl);
        return ["error" => curl_error($curl)];
    }

    curl_close($curl);

    $data = json_decode($response, true);

    if (!isset($data['matches'])) {
        return null;
    }

    foreach ($data['matches'] as $match) {
        $home = $match['homeTeam']['name'];
        $away = $match['awayTeam']['name'];
        if (
            ($home === $club1 && $away === $club2) ||
            ($home === $club2 && $away === $club1)
        ) {
            $scoreHome = $match['score']['fullTime']['home'];
            $scoreAway = $match['score']['fullTime']['away'];

            return [
                "home_team"  => $home,
                "away_team"  => $away,
                "date_time"  => $match['utcDate'],
                "status"     => $match['status'],
                "score_home" => $scoreHome,
                "score_away" => $scoreAway
            ];
        }
    }
}



function majScoreMatch($pdo, $id, $score1, $score2) {
    echo "<pre>DEBUG:\n";
    var_dump($id, $score1, $score2);
    echo "</pre>";

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare("
        UPDATE matchs
        SET score1 = ?, score2 = ?
        WHERE id = ?
    ");
    $stmt->execute([$score1, $score2, $id]);

    echo "Nombre de lignes affectées : " . $stmt->rowCount() . "<br>";
}