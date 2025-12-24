<?php
session_start();
require 'connect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE user_email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $error = "Cet email est déjà utilisé.";
    } else {
        // Hash du mot de passe
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Permission = 2 (Utilisateur)
        $stmt = $pdo->prepare("
            INSERT INTO users (user_username, user_email, user_password, user_permission)
            VALUES (?, ?, ?, 2)
        ");

        $stmt->execute([$username, $email, $hash]);

        header("Location: login.php?created=1");
        exit;
    }
}
?>

<?php include '../layout/header.php'; ?>

<div class="container mt-5">
    <div class="col-md-5 mx-auto">
        <div class="card">
            <div class="card-header text-center">
                <h4>Créer un compte</h4>
            </div>
            <div class="card-body">

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">

                    <div class="mb-3">
                        <label>Nom d'utilisateur</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label>Mot de passe</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>

                    <button class="btn btn-success w-100">Créer mon compte</button>
                </form>

            </div>
        </div>
    </div>
</div>
