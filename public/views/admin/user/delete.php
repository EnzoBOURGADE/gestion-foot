<?php
require_once "../../connect/connect.php";
require_once "../../connect/session.php";

check_login();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = (int) $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = :id");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['flash_message'] = "L'utilisateur a bien été supprimé !";
        $_SESSION['flash_type'] = "success";
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['flash_message'] = "Erreur lors de la suppression de l'utilisateur.";
        $_SESSION['flash_type'] = "probleme";
    }
} else {
    echo "ID de l'utilisateur invalide.";
}