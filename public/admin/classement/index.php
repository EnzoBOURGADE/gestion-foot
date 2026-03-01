<?php
require_once "../../login/connect.php";
require_once "../../login/session.php";

check_login();

require "../../../templates/head.php";
require "../../../templates/sidebar.php";
?>

<?php
$stmt = $pdo->query("
    SELECT u.username, u.token, u.id
    FROM users u
    ORDER BY u.token DESC
");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="d-flex flex-wrap gap-3 p-4" style="height: 100vh; overflow: hidden; width: 83%">
    <div class="card flex-grow-1" style="width: 40%; max-height: 95vh;">
        <div class="card-header">
            <div class="container mt-4">
                <h1 class="text-center">Classement</h1>

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
            </div>
        </div>

        <div class="card-body overflow-auto" style="max-height: 75vh;">
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                    <tr>
                        <th>Classement</th>
                        <th>Pseudo</th>
                        <th>Nombre Token</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $pos = 1; foreach ($users as $u): ?>
                        <tr>
                            <td><?= $pos++; ?></td>
                            <td><?= htmlspecialchars($u['username']) ?></td>
                            <td><?= htmlspecialchars($u['token']) ?></td>
                            <td>
                                <a href="form.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-primary" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="delete.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" title="Supprimer"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
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
</main>