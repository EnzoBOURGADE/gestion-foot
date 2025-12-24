<?php
require_once "../../connect/connect.php";
require_once "../../connect/session.php";

check_login();

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$name = $_POST['club_name'];
$country = (int) $_POST['club_country'];
$coach = (int) $_POST['club_coach'];
$ucl = (int) $_POST['palmares_ucl'];
$championnat = (int) $_POST['palmares_championnat'];
$wc = (int) $_POST['palmares_wc'];
$palmares = 8;



$is_edit = isset($_POST['club_id']) && is_numeric($_POST['club_id']);

if ($is_edit) {
    $id = (int) $_POST['club_id'];
    $stmt = $pdo->prepare("
        UPDATE club
        SET club_name = ?, club_country = ?, club_coach = ?, club_palmares = ?
        WHERE club_id = ?
    ");
    $stmt->execute([$name, $country, $coach, $palmares, $id]);

    $_SESSION['flash_message'] = "Le club a été mis à jour.";
    $_SESSION['flash_type'] = "success";

} else {
    $stmt = $pdo->prepare("
        INSERT INTO club (club_name, club_country, club_coach, club_palmares)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$name, $country, $coach, $palmares]);

    $_SESSION['flash_message'] = "Le club a été ajouté.";
    $_SESSION['flash_type'] = "success";
}

header("Location: index.php");
exit;
