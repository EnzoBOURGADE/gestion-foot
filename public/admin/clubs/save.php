<?php
require_once "../../login/connect.php";
require_once "../../login/session.php";

check_login();

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$name = $_POST['name'];
$country_id = (int) $_POST['country_id'];
$point = (int) $_POST['point'];



$is_edit = isset($_POST['id']) && is_numeric($_POST['id']);

var_dump($_POST);


if ($is_edit) {
    $id = (int) $_POST['id'];
    $stmt = $pdo->prepare("
        UPDATE club
        SET name = ?, country_id = ?, point = ?
        WHERE id = ?
    ");
    $stmt->execute([$name, $country_id, $point, $id]);

    $_SESSION['flash_message'] = "Le club a été mis à jour.";
    $_SESSION['flash_type'] = "success";

} else {
    $stmt = $pdo->prepare("
        INSERT INTO club (name, country_id, point)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$name, $country_id, $point]);

    $_SESSION['flash_message'] = "Le club a été ajouté.";
    $_SESSION['flash_type'] = "success";
}

header("Location: index.php");
exit;

