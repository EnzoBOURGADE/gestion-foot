<?php
require_once "../../../login/connect.php";
require_once "../../../login/session.php";

check_login();

require "../../../../config/getTeams.php";
require "../../../../templates/sidebar.php";
require "../../../../templates/head.php";
?>

<?php
$stmt1 = $pdo->query("
    SELECT c.id, c.name, c.point, cou.name AS country_name 
    FROM club c
    INNER JOIN country cou ON c.country_id = cou.id
    WHERE cou.name = 'Espagne'
    ORDER BY c.point DESC
");
$clubs = $stmt1->fetchAll(PDO::FETCH_ASSOC);

$stmtDate = $pdo->query("
    SELECT the_day, date_begin, date_end, competition
    FROM day_compet
    WHERE competition = 'PD'
");

$periodesTrue = $stmtDate->fetchAll(PDO::FETCH_ASSOC);

$today = new DateTime();
$champ_day = 1;

foreach ($periodesTrue as $periode) {
    $start = new DateTime($periode['date_begin']);
    if ($today >= $start) {
        $champ_day = (int)$periode['the_day'];
    } else {
        break;
    }
}

$stmt2 = $pdo->query("
    SELECT 
    m.id,
    cl1.name AS club1, 
    cl2.name AS club2, 
    m.score1, 
    m.score2, 
    m.date_match, 
    m.hour_match, 
    m.updated_at
FROM matchs m
INNER JOIN club cl1 ON m.club1 = cl1.id
INNER JOIN club cl2 ON m.club2 = cl2.id
INNER JOIN country cou ON cl1.country_id = cou.id
WHERE cou.name = 'Espagne'
ORDER BY 
    CASE 
        WHEN NOW() BETWEEN 
            CONCAT(m.date_match, ' ', m.hour_match)
            AND DATE_ADD(CONCAT(m.date_match, ' ', m.hour_match), INTERVAL 2 HOUR)
        THEN 1
        WHEN NOW() < CONCAT(m.date_match, ' ', m.hour_match)
        THEN 2
        ELSE 3
    END,
    m.date_match ASC,
    m.hour_match ASC;
");
$matchs = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="d-flex flex-wrap gap-3 p-4" style="height: 100vh; overflow: hidden; width: 100%">

    <!-- Liste des clubs -->
    <div class="card flex-grow-1" style="width: 40%; max-height: 95vh;">
        <div class="card-header">
            <div class="container mt-4">
                <h1 class="text-center">Liste des clubs</h1>
                <h2 class="text-center mb-4">Liga</h2>

                <?php if (isset($_SESSION['flash_message1'])): ?>
                    <div class="alert alert-<?= $_SESSION['flash_type'] ?> alert-dismissible fade show" role="alert">
                        <?= $_SESSION['flash_message1'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php
                    unset($_SESSION['flash_message1']);
                    unset($_SESSION['flash_type']);
                    ?>
                <?php endif; ?>

                <div class="text-center mb-3">
                    <a href="form.php" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Ajouter un club
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body overflow-auto" style="max-height: 75vh;">
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                    <tr>
                        <th>Classement</th>
                        <th>Club</th>
                        <th>Pays</th>
                        <th>Point</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $pos = 1; foreach ($clubs as $c): ?>
                        <tr>
                            <td><?= $pos++; ?></td>
                            <td><?= htmlspecialchars($c['name']) ?></td>
                            <td><?= htmlspecialchars($c['country_name']) ?></td>
                            <td><?= htmlspecialchars($c['point']) ?></td>
                            <td>
                                <a href="form.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-primary" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="delete.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-danger" title="Supprimer"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce club ?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Liste des matchs -->
    <div class="card flex-grow-1" style="width: 30%; max-height: 95vh;">
        <div class="card-header">
            <div class="container mt-4">
                <h1 class="text-center">Prochains matchs</h1>
                <h2 class="text-center mb-4">Liga</h2>

                <?php if (isset($_SESSION['flash_message2'])): ?>
                    <div class="alert alert-<?= $_SESSION['flash_type'] ?> alert-dismissible fade show" role="alert">
                        <?= $_SESSION['flash_message2'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php
                    unset($_SESSION['flash_message2']);
                    unset($_SESSION['flash_type']);
                    ?>
                <?php endif; ?>

                <div class="text-center mb-3">
                    <a href="formMatch.php" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Ajouter un match
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body overflow-auto" style="max-height: 75vh;">
            <div class="row g-3">
                <h4> Journée <?= $champ_day ?>/38 </h4>
                <?php
                $matchsEnded = [];
                foreach ($matchs as $m):
                    $score1 = isset($m['score1']) ? htmlspecialchars($m['score1']) : "?";
                    $score2 = isset($m['score2']) ? htmlspecialchars($m['score2']) : "?";
                    $match_hour = (new DateTime($m['hour_match']))->format('H\hi');

                    $now = new DateTime();
                    $matchDateTime = new DateTime($m['date_match'] . ' ' . $m['hour_match']);
                    $matchEndTime = clone $matchDateTime;
                    $matchEndTime->modify('+2 hours');

                    if ($now < $matchDateTime) {
                        $state = "prochainement";
                        $color_state = "bg-primary";
                    } elseif ($now >= $matchDateTime && $now <= $matchEndTime) {
                        $state = "en direct";
                        $color_state = "bg-success";
                    } elseif ($now <= $matchDateTime || $now >= $matchEndTime) {
                        if($m["score1"] == "" || $m["score2"] == "") {
                            $matchsEnded[] = $m;
                        }
                        $state = "fini";
                        $color_state = "bg-danger";
                    }
                    ?>

                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header text-center bg-primary text-white">
                                <h5 class="mb-0"><?= htmlspecialchars($m['club1']) ?> <span class="text-warning">VS</span> <?= htmlspecialchars($m['club2']) ?></h5>
                            </div>
                            <div class="card-body text-center">
                                <div class="mb-2">
                                    <span class="badge bg-info text-dark me-2"><?= htmlspecialchars($m['date_match']) ?></span>
                                    <span class="badge bg-secondary me-2"><?= $match_hour ?></span>
                                    <span class="badge <?= $color_state ?> me-2"><?= $state ?></span>
                                </div>
                                <h3 class="display-6"><?= $score1 ?> <span class="text-muted">-</span> <?= $score2 ?></h3>
                            </div>
                            <div class="card-footer">
                                <div class="text-center mb-3">
                                    <a href="formMatch.php?id=<?= $m['id'] ?>" class="btn btn-sm btn-warning" title="Modifier">
                                        Modifier le match
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach;
                if(count($matchsEnded) > 0) {
                    $matchApi = [];
                    foreach($matchsEnded as $m) {
                        $club_name1 = $m['club1'];
                        $club_name2 = $m['club2'];

                        $clubRequest = $pdo->prepare("
                        SELECT api_name FROM club
                        WHERE name = ?
                        ");

                        $clubRequest->execute([$club_name1]);
                        $club_api1 = $clubRequest->fetch(PDO::FETCH_ASSOC)['api_name'] ?? null;

                        $clubRequest->execute([$club_name2]);
                        $club_api2 = $clubRequest->fetch(PDO::FETCH_ASSOC)['api_name'] ?? null;

                        $matchApi[] = [
                            'club1' => $club_api1,
                            'club2' => $club_api2,
                            'date'  => $m['date_match']
                        ];
                    }
                    $scoresByMatch = getScore($pdo, $matchApi, "PD");
                    foreach ($scoresByMatch as $score) {
                        majScoreMatch($pdo, $score['id'], $score['score_home'], $score['score_away']);
                    }
                }
                ?>
            </div>
        </div>
    </div>
</main>