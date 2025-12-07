<?php
require_once "../connect/connect.php";
require_once "../connect/session.php";

check_login();

$is_edit = isset($_GET['id']) && is_numeric($_GET['id']);
$country = [
    'country_name' => '',
    'country_continent' => '',
    'country_palmares' => '',
];

$palmares = [
    'palmares_country_wc' => 0,
    'palmares_country_titre_conti' => 0
];

if ($is_edit) {
    $id = (int) $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM country WHERE country_id = ?");
    $stmt->execute([$id]);
    $country = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$country) die("Pays introuvable."); //J'en suis ici !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    $pal_stmt = $pdo->prepare("SELECT * FROM palmares_club WHERE palmares_club_id = ?");
    $pal_stmt->execute([$club['club_palmares']]);
    $palmarès_data = $pal_stmt->fetch(PDO::FETCH_ASSOC);
    if ($palmarès_data) {
        $palmares = $palmarès_data;
    }
}

$coach_stmt = $pdo->query("SELECT coach_id, coach_name, coach_surname FROM coach ORDER BY coach_surname");
$coach = $coach_stmt->fetchAll(PDO::FETCH_ASSOC);

$countries_stmt = $pdo->query("SELECT country_id, country_name FROM country ORDER BY country_name");
$countries = $countries_stmt->fetchAll(PDO::FETCH_ASSOC);

include '../layout/header.php';
?>

<div class="container mt-4">
    <h2><?= $is_edit ? "Modifier le club" : "Ajouter un club" ?></h2>

    <form action="save.php" method="POST">
        <?php if ($is_edit): ?>
            <input type="hidden" name="club_id" value="<?= $club['club_id'] ?>">
        <?php endif; ?>

        <div class="mb-4">
            <label class="form-label">Nom</label>
            <input type="text" name="club_name" class="form-control" value="<?= htmlspecialchars($club['club_name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Pays</label>
            <select name="club_country" class="form-select" required>
                <option value="">-- Choisir un pays --</option>
                <?php foreach ($countries as $n): ?>
                    <option value="<?= $n['country_id'] ?>" <?= ($club['club_country'] == $n['country_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($n['country_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Coach</label>
            <select name="club_coach" class="form-select" required>
                <option value="">-- Choisir un coach --</option>
                <?php foreach ($coach as $c): ?>
                    <option value="<?= $c['coach_id']?>" <?= ($club['club_coach'] == $c['coach_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['coach_name'] . " " . $c['coach_surname']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <br>
        <h4> -- Palmarès --</h4>
        <br>

        <div class="mb-3">
            <label class="form-label">Palmarès LDC</label>
            <input type="number" name="palmares_ucl" class="form-control" value="<?= htmlspecialchars($palmares['palmares_club_ucl']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Championnat National</label>
            <input type="number" name="palmares_championnat" class="form-control" value="<?= htmlspecialchars($palmares['palmares_club_championnat']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Coupes du Monde des Clubs</label>
            <input type="number" name="palmares_wc" class="form-control" value="<?= htmlspecialchars($palmares['palmares_club_wc']) ?>" required>
        </div>

        <button type="submit" class="btn btn-success"><?= $is_edit ? "Enregistrer les modifications" : "Ajouter le club" ?></button>
        <a href="index.php" class="btn btn-secondary">Retour</a>
    </form>
</div>

<?php include '../layout/footer.php'; ?>
