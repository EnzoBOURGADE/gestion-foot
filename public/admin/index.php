<?php
require_once "../login/connect.php";
require_once "../login/session.php";

check_login();

require "../../templates/head.php";
require "../../templates/sidebar.php";
?>

    <main class="flex-grow-1 bg-light p-4 overflow-auto">

        <div class="container mt-4">

            <h1> Bienvenue sur la page d'accueil </h1>
            <p> C'est génial </p>
        </div>

    </main>