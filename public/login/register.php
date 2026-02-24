<?php
session_start();
require 'connect.php';

$error = "";

$stmtNationality = $pdo->prepare("SELECT id, name FROM country ORDER BY name ASC");
$stmtNationality->execute();
$nationalities = $stmtNationality->fetchAll(PDO::FETCH_ASSOC);

$stmtClub = $pdo->prepare("SELECT id, name FROM club ORDER BY name ASC");
$stmtClub->execute();
$clubs = $stmtClub->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $name = trim($_POST["name"]);
    $surname = trim($_POST["surname"]);
    $birthday = trim($_POST["birthday"]);
    $nationality = trim($_POST["nationnality"]);
    $fav_club = trim($_POST["favClub"]);
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        $error = "Cet email est déjà utilisé.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO users (name, surname, username, nationnality, fav_club, token, email, birthday, password, permission_id, status_user)
            VALUES (?, ?, ?, ?, ?, 20, ?, ?, ?, 1, 1)
        ");

        $stmt->execute([$name, $surname, $username, $nationality, $fav_club, $email, $birthday, $hash]);

        header("Location: ./login.php");
        exit;
    }
}
?>

<?php
    require "../../templates/head.php";
?>

<div class="container mt-5">
    <div class="col-md-7 mx-auto">
        <div class="card">
            <div class="card-header text-center">
                <h4>Créer un compte</h4>
            </div>
            <div class="card-body">

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4 form-floating">
                            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" placeholder="Nom" required>
                            <label for="name">Nom</label>
                        </div>
                        <div class="col-md-4 form-floating">
                            <input type="text" class="form-control" name="surname" value="<?= htmlspecialchars($_POST['surname'] ?? '') ?>" placeholder="Nom de famille" required>
                            <label for="surname">Nom de famille</label>
                        </div>
                        <div class="col-md-4 form-floating">
                            <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" placeholder="Pseudo" required>
                            <label for="username">Nom d'utilisateur</label>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6 form-floating">
                            <input type="email" class="form-control" name="email" id="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="Email" required>
                            <label for="email">Email</label>
                        </div>
                        <div class="col-md-6 form-floating">
                            <input type="date" class="form-control" name="birthday" id="birthday" value="<?= htmlspecialchars($_POST['birthday'] ?? '') ?>" placeholder="Date de naissance" required>
                            <label for="birthday">Date de naissance</label>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6 form-floating">
                            <select class="form-select" id="nationnality" name="nationnality">
                                <option value="" disabled <?= empty($_POST['nationnality']) ? 'selected' : '' ?>>
                                    --- Nationnalité ---
                                </option>

                                <?php foreach ($nationalities as $n) { ?>
                                    <option value="<?= htmlspecialchars($n['id']) ?>"
                                        <?= (($_POST['nationnality'] ?? '') === $n['name']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($n['name']) ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <label for="nationnality">Nationnalité</label>
                        </div>

                        <div class="col-md-6 form-floating">
                            <select class="form-select" id="favClub" name="favClub">
                                <option value="" disabled <?= empty($_POST['favClub']) ? 'selected' : '' ?>>
                                    --- Club ---
                                </option>

                                <?php foreach ($clubs as $c) { ?>
                                    <option value="<?= htmlspecialchars($c['id']) ?>"
                                        <?= (($_POST['favClub'] ?? '') === $c['name']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['name']) ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <label for="favClub">Club Favori</label>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-12 form-floating">
                            <input type="password" class="form-control" name="password" value="<?= htmlspecialchars($_POST['password'] ?? '') ?>" placeholder="Mot de passe" required>
                            <label for="password">Mot de passe</label>
                        </div>
                    </div>

                    <button class="btn btn-success w-100">Créer mon compte</button>
                </form>

            </div>
        </div>
    </div>
</div>