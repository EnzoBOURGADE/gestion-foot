<aside class="col-2 bg-dark text-white p-3 d-flex flex-column justify-content-between position-fixed vh-100">

        <?php
        $sidebarJson = file_get_contents(ROOT . '/public/assets/json/sidebar.json');
        $sidebarItems = json_decode($sidebarJson, true)['sidebar'];

        $isAdmin = false;
        if (isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['id'];

            $stmt = $pdo->prepare("SELECT permission_id FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $permission = $stmt->fetchColumn();
            $isAdmin = ($permission == 2);
        }
        ?>



    <div class="text-center">
        <div>
            <h4 class="mb-4">SideBar</h4>
            <nav class="nav flex-column">
                <?php foreach($sidebarItems as $item):
                    $prefix = $isAdmin ? 'admin' : 'user';
                    ?>
                    <a class="nav-link text-white px-0 d-flex align-items-center" href="/public/<?= $prefix . $item['url'] ?>">
                        <?php if(!empty($item['icon'])): ?>
                            <i class="bi <?= $item['icon'] ?> me-2"></i>
                        <?php endif; ?>
                        <?= $item['title'] ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>
            <?php if(isset($_SESSION['user'])): ?>
                <div class="dropdown mt-5">
                    <button class="btn btn-dark p-2" type="button" id="profileMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-regular fa-circle-user" style="font-size: 25px"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="profileMenu">
                        <li>
                            <form action="" method="post" class="m-0">
                                <button type="submit" class="dropdown-item"><?php echo $_SESSION['user']['username']?></button>
                            </form>
                        </li>
                        <li>
                            <form action="../../login/logout.php" method="post" class="m-0">
                                <button type="submit" class="dropdown-item">Déconnexion</button>
                            </form>
                        </li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="/public/login/login.php">
                    <button class="btn btn-dark rounded-circle p-2">
                        <i class="fa-regular fa-circle-user" style="font-size: 25px"></i>
                    </button>
                </a>
            <?php endif; ?>

        </div>

    </aside>

    <div class="flex-grow-1" style="margin-left: 8%">