<?php
require_once "../../../login/connect.php";
require_once "../../../login/session.php";

check_login();

require "../../../../templates/head.php";
require "../../../../templates/sidebar.php";

?>

<?php

$is_edit = isset($_GET['id']) && is_numeric($_GET['id']);
$match = [
'club1' => '',
'club2' => '',
'score1' => '',
'score2' => '',
'hour_match' => '',
'date_match' => ''
];

if ($is_edit) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT club1, club2, score1, score2, hour_match, date_match FROM matchs WHERE id = ?");
    $stmt->execute([$id]);
    $match = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$match) die("Match introuvable.");
}

$clubsStmt = $pdo->prepare("SELECT id, name FROM club WHERE country_id IN (SELECT id FROM country WHERE name='Espagne') ORDER BY name ASC");
$clubsStmt->execute();
$clubs = $clubsStmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container mt-4">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header text-center">
                <h2><?= $is_edit ? "Modifier le match" : "Ajouter un match" ?></h2>
            </div>
            <div class="card-body">
                <form action="saveMatch.php" method="POST">
                    <?php if ($is_edit): ?>
                        <input type="hidden" name="id" value="<?= $id ?>">
                    <?php endif; ?>

                    <div class="row align-items-end mb-4 text-center">
                        <div class="col-md-5">
                            <label class="form-label">Club 1</label>
                            <select name="club1" class="form-select" required>
                                <option value="">-- Sélectionner un club --</option>
                                <?php foreach ($clubs as $club): ?>
                                    <option
                                            value="<?= $club['id'] ?>"
                                        <?= ($club['id'] == $match['club1']) ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars($club['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-2 d-flex justify-content-center align-items-center">
                            <h2 class="mb-0 fw-bold">VS</h2>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label">Club 2</label>
                            <select name="club2" class="form-select" required>
                                <option value="">-- Sélectionner un club --</option>
                                <?php foreach ($clubs as $club): ?>
                                    <option
                                            value="<?= $club['id'] ?>"
                                        <?= ($club['id'] == $match['club2']) ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars($club['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <?php if ($is_edit): ?>
                        <div class="row align-items-end mb-4 text-center">
                            <div class="col-md-5">
                                <label class="form-label">Score</label>
                                <input type="number" name="score1" class="form-control" value="<?= htmlspecialchars($match['score1']) ?>">
                            </div>

                            <div class="col-md-2 d-flex justify-content-center align-items-center">
                                <h2 class="mb-0 fw-bold">-</h2>
                            </div>


                            <div class="col-md-5">
                                <label class="form-label">Score</label>
                                <input type="number" name="score2" class="form-control" value="<?= htmlspecialchars($match['score2']) ?>">
                            </div>
                        </div>
                    <?php endif; ?>


                    <div class="row align-items-end mb-4 text-center">
                        <div class="mb-4 col-md-6">
                            <label class="form-label">Date</label>
                            <input type="date" name="date_match" class="form-control" value="<?= htmlspecialchars($match['date_match']) ?>" required>
                        </div>

                        <div class="mb-4 col-md-6">
                            <label class="form-label">Heure</label>
                            <input type="time" name="hour_match" class="form-control" value="<?= htmlspecialchars($match['hour_match']) ?>" required>
                        </div>
                    </div>
                        <button type="submit" class="btn btn-success"><?= $is_edit ? "Enregistrer les modifications" : "Ajouter le match" ?></button>
                        <a href="./index.php" class="btn btn-secondary">Retour</a>
                </form>
            </div>
        </div>
    </div>
</div>



