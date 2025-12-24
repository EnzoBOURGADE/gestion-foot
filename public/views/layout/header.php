<?php
if (file_exists('../../connect/session.php')) {
    require_once '../../connect/session.php';
}
else {
    require_once '../connect/session.php';
}
include 'head.php';
?>

<style>

    .dropdown:hover .dropdown-menu {
        display: block; margin-top: 0;
    }

    .nav-buttons a {
        margin-left: 50px;
    }

    #profileDropdown {
        margin-left: 700px;
    }



</style>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="../player/index.php">⚽ FootDB</a>

    <div class="ms-auto d-flex align-items-center">
        <?php if (isset($_SESSION['user'])): ?>
            <div class="nav-buttons">
                <?php
                $prefix = is_admin() ? 'user' : 'admin';
                $pages = ['club', 'player', 'coach', 'country', 'user'];
                if (is_admin()) $pages[] = 'users';
                foreach ($pages as $page): ?>
                    <a class="btn btn-outline-light" href="../../<?= $prefix ?>/<?= $page ?>/index.php">
                        <?= ucfirst($page) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="dropdown ms-3" id="profileDropdownWrapper">
            <a class="text-white fs-4 dropdown-toggle d-flex align-items-center" id="profileDropdown" role="button" style="border:none; background:none; padding:0;">
                <i class="bi bi-person-circle"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                <?php if (isset($_SESSION['user'])): ?>
                    <li><span class="dropdown-item">👤 <?= htmlspecialchars($_SESSION['user']['user_username']) ?></span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../../connect/logout.php">Se déconnecter</a></li>
                <?php else: ?>
                    <li><a class="dropdown-item" href="../../connect/login.php">Se connecter / S'inscrire</a></li>
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
