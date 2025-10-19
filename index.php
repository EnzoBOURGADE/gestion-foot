<?php
require_once 'connect.php';

$stmt = $pdo->query("SELECT * FROM players ORDER BY lastname");
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
            <td><?= htmlspecialchars($p['id']) ?></td>
            <td><?= htmlspecialchars($p['lastname']) ?></td>
            <td><?= htmlspecialchars($p['firstname']) ?></td>
            <td><?= htmlspecialchars($p['position']) ?></td>
            <td><?= htmlspecialchars($p['club']) ?></td>
            <td><?= htmlspecialchars($p['number']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
