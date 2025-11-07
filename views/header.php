<?php
require_once 'session.php';
include 'head.php';
?>

<style>
    .dropdown:hover .dropdown-menu { display: block; margin-top: 0; }
    .nav-buttons a { margin-left: 10px; }
</style>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="index.php">⚽ FootDB</a>

    <div class="ms-auto d-flex align-items-center">
        <!-- Boutons pour user/admin -->
        <?php if (isset($_SESSION['user'])): ?>
            <div class="nav-buttons">
                <?php
                $prefix = is_admin() ? 'admin' : 'user';
                $pages = ['club', 'player', 'country'];
                if (is_admin()) $pages[] = 'users'; // seulement admin
                foreach ($pages as $page): ?>
                    <a class="btn btn-outline-light btn-sm" href="./<?= $prefix ?>/<?= $page ?>/index.php">
                        <?= ucfirst($page) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Dropdown profil -->
        <div class="dropdown ms-3" id="profileDropdownWrapper">
            <a class="text-white fs-4 dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" style="border:none; background:none; padding:0;">
                <i class="bi bi-person-circle"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                <?php if (isset($_SESSION['user'])): ?>
                    <li><span class="dropdown-item">👤 <?= htmlspecialchars($_SESSION['user']['user_username']) ?></span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="logout.php">Se déconnecter</a></li>
                <?php else: ?>
                    <li><a class="dropdown-item" href="login.php">Se connecter / S'inscrire</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var dropdown = document.getElementById('profileDropdownWrapper');
        var dropdownToggle = new bootstrap.Dropdown(dropdown.querySelector('.dropdown-toggle'));
        dropdown.addEventListener('mouseenter', function () { dropdownToggle.show(); });
        dropdown.addEventListener('mouseleave', function () { dropdownToggle.hide(); });
    });
</script>
