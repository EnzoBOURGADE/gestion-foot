<?php
require_once "../../connect/connect.php";
require_once "../../connect/session.php";

check_login();

$is_edit = isset($_GET['id']) && is_numeric($_GET['id']);
$user = [
    'user_username' => '',
    'user_email' => '',
    'user_permission' => '',
    'user_password' => '',
];

if ($is_edit) {
    $id = (int) $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        die("utilisateur introuvable.");
    }
}


$permission_stmt = $pdo->query("SELECT permission_id, permission_name FROM permission ORDER BY permission_name");
$permission = $permission_stmt->fetchAll(PDO::FETCH_ASSOC);

include '../../layout/header.php';
?>

<div class="container mt-4">
    <h2><?= $is_edit ? "Modifier l'utilisateur" : "Ajouter un utilisateur" ?></h2>

    <form action="save.php" method="POST">
        <?php if ($is_edit): ?>
            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label class="form-label">Pseudo</label>
            <input type="text" name="user_username" class="form-control" value="<?= htmlspecialchars($user['user_username']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="user_email" class="form-control" value="<?= htmlspecialchars($user['user_email']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Permission</label>
            <select name="user_permission" class="form-select" required>
                <option value="">-- Choisir une permission --</option>
                <?php foreach ($permission as $p): ?>
                    <option value="<?= $p['permission_id'] ?>" <?= ($user['user_permission'] == $p['permission_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['permission_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if (!$is_edit): ?>
            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="user_password" class="form-control" value="" required>
            </div>
        <?php endif; ?>


        <button type="submit" class="btn btn-success"><?= $is_edit ? "Enregistrer les modifications" : "Ajouter l'utilisateur" ?></button>
        <a href="index.php" class="btn btn-secondary">Retour</a>
    </form>
</div>

<?php include '../../layout/footer.php'; ?>
