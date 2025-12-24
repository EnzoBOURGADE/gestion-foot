<?php
require_once "../../connect/connect.php";
require_once "../../connect/session.php";

check_login();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $club_id = (int) $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM club WHERE club_id = :id");
    $stmt->bindParam(':id', $club_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['flash_message'] = "Le club a bien été supprimé !";
        $_SESSION['flash_type'] = "success";
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['flash_message'] = "Erreur lors de la suppression du club.";
        $_SESSION['flash_type'] = "probleme";
    }
} else {
    echo "ID du club invalide.";
}