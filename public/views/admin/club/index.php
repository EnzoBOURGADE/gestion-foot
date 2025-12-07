<?php
require_once "../connect/connect.php";
require_once "../connect/session.php";

check_login();

include '../layout/header.php';

$stmt = $pdo->query("
    SELECT c.*,
           cou.country_name as country_name,
           coa.coach_name as coach_name,
           coa.coach_surname as coach_surname,
           palm.palmares_club_ucl as palmares_ucl,
           palm.palmares_club_championnat as palmares_championnat,
           palm.palmares_club_wc as palmares_club_wc
    FROM club c
    INNER JOIN country cou ON c.club_country = cou.country_id
    INNER JOIN coach coa ON coa.coach_id = c.club_coach
    INNER JOIN palmares_club palm ON palm.palmares_club_id = c.club_palmares
    ORDER BY c.club_name;
");
$clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Liste des clubs</h1>

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
            <i class="bi bi-plus-circle"></i> Ajouter un club
        </a>
    </div>

    <table class="table table-striped table-bordered text-center">
        <thead>
        <tr>
            <th>Nom</th>
            <th>Pays</th>
            <th>Coach</th>
            <th>Nombre championnat</th>
            <th>Nombre Ligue des Champions</th>
            <th>Nombre Coupe du monde des clubs</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($clubs as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c['club_name']) ?></td>
                <td><?= htmlspecialchars($c['country_name']) ?></td>
                <td>
                    <?= htmlspecialchars(
                        empty($c['coach_name']) && empty($c['coach_surname'])
                            ? "Inconnu"
                            : $c['coach_name'] . " " . $c['coach_surname']
                    ) ?>
                </td>
                <td><?= htmlspecialchars($c['palmares_championnat']) ?></td>
                <td><?= htmlspecialchars($c['palmares_ucl']) ?></td>
                <td><?= htmlspecialchars($c['palmares_club_wc']) ?></td>
                <td>
                    <a href="form.php?id=<?= $c['club_id'] ?>" class="btn btn-sm btn-primary" title="Modifier">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <a href="delete.php?id=<?= $c['club_id'] ?>" class="btn btn-sm btn-danger" title="Supprimer"
                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce club ?');">
                        <i class="bi bi-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../layout/footer.php'; ?>
