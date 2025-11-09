<?php
require_once "../connect/connect.php";
require_once "../connect/session.php";

check_login();

include '../layout/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['flash_message'] = "ID joueur invalide.";
    $_SESSION['flash_type'] = "danger";
    header("Location: index.php");
    exit();
}

$player_id = (int) $_GET['id'];

$stmt = $pdo->prepare("
    SELECT p.*, c.club_name 
    FROM player p
    INNER JOIN club c ON p.player_club = c.club_id
    WHERE p.player_id = ?
");
$stmt->execute([$player_id]);
$player = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$player) {
    $_SESSION['flash_message'] = "Joueur introuvable.";
    $_SESSION['flash_type'] = "danger";
    header("Location: index.php");
    exit();
}

$clubs_stmt = $pdo->query("SELECT club_id, club_name FROM club ORDER BY club_name");
$clubs = $clubs_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le joueur</title>

    <style>
        .grey-container {
            background: #f0f0f0;
            padding: 25px;
            border-radius: 10px;
            max-width: 700px;
            margin: 40px auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="grey-container">
    <h2 class="text-center mb-4">Modifier le joueur</h2>

    <form action="update.php" method="POST">

        <input type="hidden" name="player_id" value="<?= htmlspecialchars($player['player_id']) ?>">

        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text"
                   class="form-control"
                   name="player_name"
                   value="<?= htmlspecialchars($player['player_name']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Prénom</label>
            <input type="text"
                   class="form-control"
                   name="player_surname"
                   value="<?= htmlspecialchars($player['player_surname']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Poste</label>
            <input type="text"
                   class="form-control"
                   name="player_post"
                   value="<?= htmlspecialchars($player['player_post']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Club</label>
            <select name="player_club" class="form-select">
                <?php foreach ($clubs as $c): ?>
                    <option value="<?= $c['club_id'] ?>"
                        <?= ($player['player_club'] == $c['club_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['club_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Âge</label>
            <input type="number"
                   class="form-control"
                   name="player_age"
                   value="<?= htmlspecialchars($player['player_age']) ?>">
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success">Enregistrer</button>
            <a href="index.php" class="btn btn-secondary">Retour</a>
        </div>

    </form>
</div>

<?php include '../layout/footer.php'; ?>
</body>
</html>
