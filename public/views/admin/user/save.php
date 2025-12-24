<?php
require_once "../../connect/connect.php";
require_once "../../connect/session.php";

check_login();

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$username = $_POST['user_username'];
$mail = $_POST['user_email'];
$permission = $_POST['user_permission'];

$is_edit = isset($_POST['user_id']) && is_numeric($_POST['user_id']);

if ($is_edit) {
    $id = (int) $_POST['user_id'];
    $stmt = $pdo->prepare("
        UPDATE users
        SET user_username = ?, user_email = ?, user_permission = ?
        WHERE user_id = ?
    ");
    $stmt->execute([$username, $mail, $permission, $id]);
    if ($stmt->fetch()) {
        $error = "Cet email est déjà utilisé.";
    } else {

        $_SESSION['flash_message'] = "L'utilisateur a été mis à jour.";
        $_SESSION['flash_type'] = "success";
    }

} else {
    $password = password_hash($_POST['user_password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("
        INSERT INTO users (user_username, user_email, user_password, user_permission)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$username, $mail, $password, $permission]);
    if ($stmt->fetch()) {
        $error = "Cet email est déjà utilisé.";
    } else {

        $_SESSION['flash_message'] = "L'utilisateur a été ajouté.";
        $_SESSION['flash_type'] = "success";
    }
}

header("Location: index.php");
exit;
