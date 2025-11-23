<?php
require_once "../connect/connect.php";
require_once "../connect/session.php";

check_login();

$is_edit = isset($_GET['id']) && is_numeric($_GET['id']);
$player = [
    'player_name' => '',
    'player_surname' => '',
    'player_post' => '',
    'player_club' => '',
    'player_nationnality' => '',
    'player_age' => ''
];

if ($is_edit) {
    $id = (int) $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM player WHERE player_id = ?");
    $stmt->execute([$id]);
    $player = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$player) {
        die("Joueur introuvable.");
    }
}

// Clubs
$clubs_stmt = $pdo->query("SELECT club_id, club_name FROM club ORDER BY club_name");
$clubs = $clubs_stmt->fetchAll(PDO::FETCH_ASSOC);

// Nationalités
$countries_stmt = $pdo->query("SELECT country_id, country_name FROM country ORDER BY country_name");
$countries = $countries_stmt->fetchAll(PDO::FETCH_ASSOC);

include '../layout/header.php';
?>

<div class="container mt-4">
    <h2><?= $is_edit ? "Modifier le joueur" : "Ajouter un joueur" ?></h2>

    <form action="save.php" method="POST">
        <?php if ($is_edit): ?>
            <input type="hidden" name="player_id" value="<?= $player['player_id'] ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="player_name" class="form-control" value="<?= htmlspecialchars($player['player_name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Prénom</label>
            <input type="text" name="player_surname" class="form-control" value="<?= htmlspecialchars($player['player_surname']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Poste</label>
            <input type="text" name="player_post" class="form-control" value="<?= htmlspecialchars($player['player_post']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Club</label>
            <select name="player_club" class="form-select" required>
                <option value="">-- Choisir un club --</option>
                <?php foreach ($clubs as $c): ?>
                    <option value="<?= $c['club_id'] ?>" <?= ($player['player_club'] == $c['club_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['club_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Nationalité</label>
            <select name="player_nationnality" class="form-select" required>
                <option value="">-- Choisir une nationalité --</option>
                <?php foreach ($countries as $n): ?>
                    <option value="<?= $n['country_id'] ?>" <?= ($player['player_nationnality'] == $n['country_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($n['country_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Âge</label>
            <input type="number" name="player_age" class="form-control" value="<?= htmlspecialchars($player['player_age']) ?>" required>
        </div>

        <button type="submit" class="btn btn-success"><?= $is_edit ? "Enregistrer les modifications" : "Ajouter le joueur" ?></button>
        <a href="index.php" class="btn btn-secondary">Retour</a>
    </form>
</div>

<?php include '../layout/footer.php'; ?>
