<?php
require_once "../../connect/connect.php";
require_once "../../connect/session.php";

check_login();

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$name = $_POST['country_name'];
$continent = (int) $_POST['country_continent'];
$internationnal = (int) $_POST['palmares_internationnel'];
$wc = (int) $_POST['palmares_wc'];
$palmares = 8;



$is_edit = isset($_POST['country_id']) && is_numeric($_POST['country_id']);

if ($is_edit) {
    $id = (int) $_POST['country_id'];
    $stmt = $pdo->prepare("
        UPDATE country
        SET country_name = ?, country_continent = ?, country_palmares = ?
        WHERE club_id = ?
    ");
    $stmt->execute([$name, $continent, $palmares, $id]);

    $_SESSION['flash_message'] = "Le pays a été mis à jour.";
    $_SESSION['flash_type'] = "success";

} else {
    $stmt = $pdo->prepare("
        INSERT INTO country (country_name, country_continent, country_palmares)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$name, $continent, $palmares]);

    $_SESSION['flash_message'] = "Le pays a été ajouté.";
    $_SESSION['flash_type'] = "success";
}

header("Location: index.php");
exit;
