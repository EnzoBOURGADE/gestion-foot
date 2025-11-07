<?php
require_once 'connect.php';
require_once 'session.php';

check_login();

include 'header.php';

$stmt = $pdo->query("SELECT * FROM player");
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
<table>
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Poste</th>
        <th>Club</th>
        <th>Numéro</th>
    </tr>
    <?php foreach ($players as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['player_id']) ?></td>
            <td><?= htmlspecialchars($p['player_name']) ?></td>
            <td><?= htmlspecialchars($p['player_surname']) ?></td>
            <td><?= htmlspecialchars($p['player_post']) ?></td>
            <td><?= htmlspecialchars($p['player_club']) ?></td>
            <td><?= htmlspecialchars($p['player_age']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include 'footer.php'; ?>
</body>
</html>
