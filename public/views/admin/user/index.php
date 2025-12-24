<?php
require_once "../../connect/connect.php";
require_once "../../connect/session.php";

check_login();

include '../../layout/header.php';

$stmt = $pdo->query("
    SELECT u.*, p.permission_name AS permission
    FROM users u
    INNER JOIN permission p ON p.permission_id= u.user_permission
    ORDER BY u.user_id
");
$users= $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Liste des utilisateurs</h1>

    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="alert alert-<?= $_SESSION['flash_type'] ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['flash_message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        ?>
    <?php endif; ?>

    <div class="text-center mb-3">
        <a href="form.php" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Ajouter un utilisateur
        </a>
    </div>

    <table class="table table-striped table-bordered text-center">
        <thead>
        <tr>
            <th>Id</th>
            <th>Pseudo</th>
            <th>Mail</th>
            <th>Permission</th>
            <th>Date création</th>
            <th>Date dernière modification</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['user_id']) ?></td>
                <td><?= htmlspecialchars($u['user_username']) ?></td>
                <td><?= htmlspecialchars($u['user_email']) ?></td>
                <td><?= htmlspecialchars($u['permission']) ?></td>
                <td><?= htmlspecialchars($u['created_at']) ?></td>
                <td><?= htmlspecialchars($u['updated_at']) ?></td>
                <td>
                    <a href="form.php?id=<?= $u['user_id'] ?>" class="btn btn-sm btn-primary" title="Modifier">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <a href="delete.php?id=<?= $u['user_id'] ?>" class="btn btn-sm btn-danger" title="Supprimer"
                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                        <i class="bi bi-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../../layout/footer.php'; ?>
