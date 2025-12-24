<?php
require_once "../../connect/connect.php";
require_once "../../connect/session.php";

check_login();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $player_id = (int) $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM player WHERE player_id = :id");
    $stmt->bindParam(':id', $player_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['flash_message'] = "Le joueur a bien été supprimé !";
        $_SESSION['flash_type'] = "success";
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['flash_message'] = "Erreur lors de la suppression du joueur.";
        $_SESSION['flash_type'] = "probleme";
    }
} else {
    echo "ID du joueur invalide.";
}