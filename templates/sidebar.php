<aside class="col-1 bg-dark text-white p-3 d-flex flex-column justify-content-between position-fixed vh-100">

    <?php
    define('ROOT', dirname(__DIR__));

    $sidebarJson = file_get_contents(ROOT . '/public/assets/json/sidebar.json');
    $sidebarData = json_decode($sidebarJson, true);
    $sidebarItems = $sidebarData['sidebar'] ?? [];

    $isAdmin = false;

    if (isset($_SESSION['user'])) {
        $userId = $_SESSION['user']['id'];

        $stmt = $pdo->prepare("SELECT permission_id FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $permission = $stmt->fetchColumn();

        $isAdmin = ($permission == 2);
    }
    ?>

    <div class="d-flex flex-column h-100">

        <div>
            <h4 class="mb-4 text-center">SideBar</h4>

            <nav class="nav flex-column">

                <?php foreach ($sidebarItems as $index => $item):
                    $prefix = $isAdmin ? 'admin' : 'user';
                    $hasChildren = !empty($item['children']);
                    $collapseId = "collapseMenu" . $index;
                    ?>

                    <?php if ($hasChildren): ?>

                    <div class="nav-item">

                        <div class="d-flex justify-content-between align-items-center w-50">

                                <?php if (!empty($item['icon'])): ?>
                                    <i class="bi <?= htmlspecialchars($item['icon']) ?> me-2"></i>
                                <?php endif; ?>

                                <?= htmlspecialchars($item['title']) ?>

                            <button class="btn btn-sm text-white"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#<?= $collapseId ?>"
                                    aria-expanded="false"
                                    aria-controls="<?= $collapseId ?>">
                                <i class="bi bi-chevron-down small"></i>
                            </button>

                        </div>

                        <div class="collapse ps-3" id="<?= $collapseId ?>">
                            <?php foreach ($item['children'] as $child): ?>
                                <a class="nav-link text-white px-0"
                                   href="/public/<?= $prefix . $child['url'] ?>">

                                    <?php if (!empty($child['icon'])): ?>
                                        <i class="bi <?= htmlspecialchars($child['icon']) ?> me-2"></i>
                                    <?php endif; ?>

                                    <?= htmlspecialchars($child['title']) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>

                    </div>

                <?php else: ?>

                    <a class="nav-link text-white px-0 d-flex align-items-center"
                       href="/public/<?= $prefix . $item['url'] ?>">

                        <?php if (!empty($item['icon'])): ?>
                            <i class="bi <?= htmlspecialchars($item['icon']) ?> me-2"></i>
                        <?php endif; ?>

                        <?= htmlspecialchars($item['title']) ?>
                    </a>

                <?php endif; ?>

                <?php endforeach; ?>

            </nav>
        </div>

        <div>
            <?php if (isset($_SESSION['user'])): ?>

                <div class="dropdown">
                    <button class="btn btn-dark w-100 text-start"
                            type="button"
                            id="profileMenu"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">

                        <i class="fa-regular fa-circle-user me-2"></i>
                        <?= htmlspecialchars($_SESSION['user']['username']) ?>
                    </button>

                    <ul class="dropdown-menu w-100" aria-labelledby="profileMenu">

                        <li>
                            <form action="../../login/logout.php" method="post" class="m-0">
                                <button type="submit" class="dropdown-item">
                                    Déconnexion
                                </button>
                            </form>
                        </li>

                    </ul>
                </div>

            <?php else: ?>

                <a href="/public/login/login.php" class="btn btn-dark w-100">
                    <i class="fa-regular fa-circle-user"></i>
                    Connexion
                </a>

            <?php endif; ?>
        </div>

    </div>

</aside>

<div class="flex-grow-1" style="margin-left: 16.6667%">