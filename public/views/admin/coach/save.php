<?php
require_once "../../connect/connect.php";
require_once "../../connect/session.php";

check_login();

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$name = $_POST['player_name'];
$surname = $_POST['player_surname'];
$post = $_POST['player_post'];
$club = (int) $_POST['player_club'];
$nation = (int) $_POST['player_nationnality'];
$age = (int) $_POST['player_age'];

// Mode modification ?
$is_edit = isset($_POST['player_id']) && is_numeric($_POST['player_id']);

if ($is_edit) {
    $id = (int) $_POST['player_id'];
    $stmt = $pdo->prepare("
        UPDATE player
        SET player_name = ?, player_surname = ?, player_post = ?, player_club = ?, player_nationnality = ?, player_age = ?
        WHERE player_id = ?
    ");
    $stmt->execute([$name, $surname, $post, $club, $nation, $age, $id]);

    $_SESSION['flash_message'] = "Le joueur a été mis à jour.";
    $_SESSION['flash_type'] = "success";

} else {
    $stmt = $pdo->prepare("
        INSERT INTO player (player_name, player_surname, player_post, player_club, player_nationnality, player_age)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$name, $surname, $post, $club, $nation, $age]);

    $_SESSION['flash_message'] = "Le joueur a été ajouté.";
    $_SESSION['flash_type'] = "success";
}

header("Location: index.php");
exit;
