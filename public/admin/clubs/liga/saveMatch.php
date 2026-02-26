<?php
require_once "../../../login/connect.php";
require_once "../../../login/session.php";

check_login();

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$club1 = $_POST['club1'];
$club2 = $_POST['club2'];

$score1 = $_POST['score1'] ?? null;
$score2 = $_POST['score2'] ?? null;

$date_match = $_POST['date_match'];
$hour_match = $_POST['hour_match'];


$is_edit = isset($_POST['id']) && is_numeric($_POST['id']);

var_dump($_POST);


if ($is_edit) {
    $id = (int) $_POST['id'];
    $stmt = $pdo->prepare("
        UPDATE matchs
        SET score1 = ?, score2 = ?, date_match = ?, hour_match = ?
        WHERE id = ?
    ");
    $stmt->execute([$score1, $score2, $date_match,  $hour_match, $id]);

    $_SESSION['flash_message2'] = "Le match a été mis à jour.";
    $_SESSION['flash_type'] = "success";

} else {
    $stmt = $pdo->prepare("
        INSERT INTO matchs (club1, club2, date_match, hour_match)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$club1, $club2, $date_match, $hour_match]);

    $_SESSION['flash_message2'] = "Le match a été ajouté.";
    $_SESSION['flash_type'] = "success";
}

header("Location: index.php");
exit;

