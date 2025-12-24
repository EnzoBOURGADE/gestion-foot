<?php
require_once "../../connect/connect.php";
require_once "../../connect/session.php";

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
    if (!$country) die("Pays introuvable.");
    $pal_stmt = $pdo->prepare("SELECT * FROM palmares_country WHERE palmares_country_id = ?");
    $pal_stmt->execute([$country['country_palmares']]);
    $palmarès_data = $pal_stmt->fetch(PDO::FETCH_ASSOC);
    if ($palmarès_data) {
        $palmares = $palmarès_data;
    }
}

$countries_stmt = $pdo->query("SELECT country_id, country_name FROM country ORDER BY country_name");
$countries = $countries_stmt->fetchAll(PDO::FETCH_ASSOC);

$continent_stmt = $pdo->query("SELECT continent_id, continent_name FROM continent ORDER BY continent_name");
$continent= $continent_stmt->fetchAll(PDO::FETCH_ASSOC);

include '../../layout/header.php';
?>

<div class="container mt-4">
    <h2><?= $is_edit ? "Modifier le pays" : "Ajouter un pays" ?></h2>

    <form action="save.php" method="POST">
        <?php if ($is_edit): ?>
            <input type="hidden" name="country_id" value="<?= $country['country_id'] ?>">
        <?php endif; ?>

        <div class="mb-4">
            <label class="form-label">Nom</label>
            <input type="text" name="country_name" class="form-control" value="<?= htmlspecialchars($country['country_name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Continent</label>
            <select name="country_continent" class="form-select" required>
                <option value="">-- Choisir un continent --</option>
                <?php foreach ($continent as $c): ?>
                    <option value="<?= $c['continent_id'] ?>" <?= ($country['country_continent'] == $c['continent_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['continent_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <br>
        <h4> -- Palmarès --</h4>
        <br>

        <div class="mb-3">
            <label class="form-label">Palmarès Coupe du monde</label>
            <input type="number" name="palmares_wc" class="form-control" value="<?= htmlspecialchars($palmares['palmares_country_wc']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Palmarès International</label>
            <input type="number" name="palmares_internationnel" class="form-control" value="<?= htmlspecialchars($palmares['palmares_country_titre_conti']) ?>" required>
        </div>

        <button type="submit" class="btn btn-success"><?= $is_edit ? "Enregistrer les modifications" : "Ajouter le pays" ?></button>
        <a href="index.php" class="btn btn-secondary">Retour</a>
    </form>
</div>

<?php include '../../layout/footer.php'; ?>
