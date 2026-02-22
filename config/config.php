<?php
if ($_SERVER['SERVER_NAME'] == 'localhost') {
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'footdb');
    define('DB_USER', 'root');
    define('DB_PASS', '');
} else {
    define('DB_HOST', '');
    define('DB_NAME', '');
    define('DB_USER', '');
    define('DB_PASS', '');
}
define('ROOT', dirname(__DIR__));

try {
    $pdo = new PDO('mysql:host=localhost;dbname=footdb;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur BDD : '.$e->getMessage());
}