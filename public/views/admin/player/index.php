<?php
require_once '../connect/connect.php';
require_once '../connect/session.php';

check_login();

include '../layout/header.php';

$stmt = $pdo->query("SELECT p.*, c.club_name as name_club FROM player p INNER JOIN club c ON p.player_club = c.club_id");
$players = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des joueurs</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: 20px auto; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
<h1 style="text-align:center;">Liste des joueurs</h1>

<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?= $_SESSION['flash_type'] ?> alert-dismissible fade show" role="alert" style="width:80%; margin: 10px auto;">
        <?= $_SESSION['flash_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php
    unset($_SESSION['flash_message']);
    unset($_SESSION['flash_type']);
endif;
?>

<table>
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Poste</th>
        <th>Club</th>
        <th>Age</th>
        <th>Autres</th>
    </tr>
    <?php foreach ($players as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['player_id']) ?></td>
            <td><?= htmlspecialchars($p['player_name']) ?></td>
            <td><?= htmlspecialchars($p['player_surname']) ?></td>
            <td><?= htmlspecialchars($p['player_post']) ?></td>
            <td><?= htmlspecialchars($p['name_club']) ?></td>
            <td><?= htmlspecialchars($p['player_age'] . " ans")?></td>
            <td>
                <a href="edit.php?id=<?= $p['player_id'] ?>" class="btn btn-sm btn-primary" title="Modifier">
                    <i class="bi bi-pencil"></i>
                </a>
                <a href="delete.php?id=<?= $p['player_id'] ?>" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce joueur ?');">
                    <i class="bi bi-trash"></i>
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include '../layout/footer.php'; ?>
</body>
</html>
