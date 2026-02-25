<?php
require_once "../../../login/connect.php";
require_once "../../../login/session.php";

check_login();

require "../../../../templates/head.php";
require "../../../../templates/sidebar.php";

?>

<?php

$is_edit = isset($_GET['id']) && is_numeric($_GET['id']);
$club = [
'name' => '',
'country_id' => '',
'point' => 0,
];

if ($is_edit) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT id, name, country_id, point FROM club WHERE id = ?");
    $stmt->execute([$id]);
    $club = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$club) die("Club introuvable.");
}
?>

<div class="container mt-4">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header text-center">
                <h2><?= $is_edit ? "Modifier le club" : "Ajouter un club" ?></h2>
            </div>
            <div class="card-body">
                <form action="save.php" method="POST">
                    <?php if ($is_edit): ?>
                        <input type="hidden" name="id" value="<?= $club['id'] ?>">
                    <?php endif; ?>

                    <div class="mb-4">
                        <label class="form-label">Nom</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($club['name']) ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Point</label>
                        <input type="number" name="point" class="form-control" value="<?= htmlspecialchars($club['point']) ?>" required>
                    </div>

                    <button type="submit" class="btn btn-success"><?= $is_edit ? "Enregistrer les modifications" : "Ajouter le club" ?></button>
                    <a href="./index.php" class="btn btn-secondary">Retour</a>
                </form>
            </div>
        </div>
    </div>
</div>



