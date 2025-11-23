<?php
require_once "../connect/connect.php";
require_once "../connect/session.php";

check_login();

include '../layout/header.php';

// Récupération des joueurs
$stmt = $pdo->query("
    SELECT p.*, c.club_name as name_club, co.country_name as name_nationnality 
    FROM player p
    INNER JOIN club c ON p.player_club = c.club_id
    INNER JOIN country co ON p.player_nationnality = co.country_id
    ORDER BY p.player_id
");
$players = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Liste des joueurs</h1>

    <!-- Flash message -->
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

    <!-- Bouton Ajouter -->
    <div class="text-center mb-3">
        <a href="form.php" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Ajouter un joueur
        </a>
    </div>

    <!-- Table -->
    <table class="table table-striped table-bordered text-center">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Poste</th>
            <th>Club</th>
            <th>Nationalité</th>
            <th>Âge</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($players as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['player_id']) ?></td>
                <td><?= htmlspecialchars($p['player_name']) ?></td>
                <td><?= htmlspecialchars($p['player_surname']) ?></td>
                <td><?= htmlspecialchars($p['player_post']) ?></td>
                <td><?= htmlspecialchars($p['name_club']) ?></td>
                <td><?= htmlspecialchars($p['name_nationnality']) ?></td>
                <td><?= htmlspecialchars($p['player_age']) ?> ans</td>
                <td>
                    <a href="form.php?id=<?= $p['player_id'] ?>" class="btn btn-sm btn-primary" title="Modifier">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <a href="delete.php?id=<?= $p['player_id'] ?>" class="btn btn-sm btn-danger" title="Supprimer"
                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce joueur ?');">
                        <i class="bi bi-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../layout/footer.php'; ?>
