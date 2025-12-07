<?php
require_once "../connect/connect.php";
require_once "../connect/session.php";

check_login();

include '../layout/header.php';

$stmt = $pdo->query("
    SELECT c.*, 
           cont.continent_name, 
           palm.palmares_country_wc, 
           palm.palmares_country_titre_conti
    FROM country c
    INNER JOIN palmares_country palm 
        ON palm.palmares_country_id = c.country_palmares
    INNER JOIN continent cont 
        ON cont.continent_id = c.country_continent
    GROUP BY c.country_id
");

$country = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Liste des pays</h1>

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
            <i class="bi bi-plus-circle"></i> Ajouter un pays
        </a>
    </div>

    <table class="table table-striped table-bordered text-center">
        <thead>
        <tr>
            <th>Nom</th>
            <th>Continent</th>
            <th>Nombre coupe du monde</th>
            <th>Nombre titres continentaux</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($country as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c['country_name']) ?></td>
                <td><?= htmlspecialchars($c['continent_name']) ?></td>
                <td><?= htmlspecialchars($c['palmares_country_wc']) ?></td>
                <td><?= htmlspecialchars($c['palmares_country_titre_conti']) ?></td>
                <td>
                    <a href="form.php?id=<?= $c['country_id'] ?>" class="btn btn-sm btn-primary" title="Modifier">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <a href="delete.php?id=<?= $c['country_id'] ?>" class="btn btn-sm btn-danger" title="Supprimer"
                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce pays ?');">
                        <i class="bi bi-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../layout/footer.php'; ?>
