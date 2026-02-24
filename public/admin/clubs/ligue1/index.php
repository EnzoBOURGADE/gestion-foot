<?php
require_once "../../../login/connect.php";
require_once "../../../login/session.php";

check_login();

require "../../../../templates/head.php";
require "../../../../templates/sidebar.php";

?>

<?php
$stmt = $pdo->query("
    SELECT c.id, c.name, c.point, cou.name AS country_name FROM club c
    INNER JOIN country cou ON c.country_id = cou.id
    WHERE cou.name = 'France'
    ORDER BY c.point DESC 
    ");
$clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <main class="flex-grow-1 bg-light p-4 overflow-auto">

        <div class="container mt-4">
            <h1 class="text-center">Liste des clubs</h1>
            <h2 class="text-center mb-4"> Ligue1 </h2>

            <?php if (isset($_SESSION['flash_message'])): ?>
                <div class="alert alert-<?= $_SESSION['flash_type'] ?> alert-dismissible fade show" role="alert">
                    <?= $_SESSION['flash_message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php
                unset($_SESSION['flash_message']);
                unset($_SESSION['flash_type']);
                ?>
            <?php endif; ?>

            <div class="text-center mb-3">
                <a href="form.php" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Ajouter un club
                </a>
            </div>

            <table class="table table-striped table-bordered text-center">
                <thead>
                <tr>
                    <th>Classement</th>
                    <th>Club</th>
                    <th>Pays</th>
                    <th>Point</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php $pos = 1;
                foreach ($clubs as $c): ?>
                    <tr>
                        <td> <?php echo $pos;
                            $pos++; ?></td>

                        <td><?= htmlspecialchars($c['name']) ?></td>
                        <td><?= htmlspecialchars($c['country_name']) ?></td>
                        <td><?= htmlspecialchars($c['point']) ?></td>
                        <td>
                            <a href="form.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-primary" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="delete.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-danger" title="Supprimer"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce club ?');">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>


    </main>