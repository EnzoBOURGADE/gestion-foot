<?php
require_once "../../connect/connect.php";
require_once "../../connect/session.php";

check_login();

include '../../layout/header.php';

$stmt = $pdo->query("
    SELECT c.*, coun.country_name as country
    FROM coach c
    INNER JOIN country coun ON c.coach_nationnality = coun.country_id
    ORDER BY c.coach_id
");
$coach = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Liste des coachs</h1>

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
            <i class="bi bi-plus-circle"></i> Ajouter un coach
        </a>
    </div>

    <table class="table table-striped table-bordered text-center">
        <thead>
        <tr>
            <th>Id</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Âge</th>
            <th>Nationalité</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($coach as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c['coach_id']) ?></td>
                <td><?= htmlspecialchars($c['coach_name']) ?></td>
                <td><?= htmlspecialchars($c['coach_surname']) ?></td>
                <td><?= htmlspecialchars($c['coach_age']) ?> ans</td>
                <td><?= htmlspecialchars($c['country']) ?></td>
                <td>
                    <a href="form.php?id=<?= $c['coach_id'] ?>" class="btn btn-sm btn-primary" title="Modifier">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <a href="delete.php?id=<?= $c['coach_id'] ?>" class="btn btn-sm btn-danger" title="Supprimer"
                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce coach ?');">
                        <i class="bi bi-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../../layout/footer.php'; ?>
