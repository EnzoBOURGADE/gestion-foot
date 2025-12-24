<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function check_login() {
    if (!isset($_SESSION['user'])) {
        header("Location: ../connect/login.php");
        exit();
    }
}

function is_admin() {
    return isset($_SESSION['user']) && $_SESSION['user']['user_permission'] === 1;
}
?>
