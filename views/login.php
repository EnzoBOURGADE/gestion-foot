<?php
require 'connect.php';
require 'session.php';

$error = "";
$showSuccess = isset($_GET['created']) && $_GET['created'] == 1;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user["user_password"])) {
        $error = "Email ou mot de passe incorrect.";
    } else {
        $_SESSION["user"] = $user;
        header("Location: index.php");
        exit;
    }
}
?>

<?php include 'header.php'; ?>

<div class="container mt-5">
    <div class="col-md-4 mx-auto">
        <div class="card">
            <div class="card-header text-center">
                <h4>Connexion</h4>
            </div>
            <div class="card-body">

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <!-- Pop-up succès inscription -->
                <?php if ($showSuccess): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        ✅ Votre compte a été créé avec succès ! Vous pouvez vous connecter.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label>Mot de passe</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>

                    <button class="btn btn-primary w-100">Se connecter</button>

                    <p class="mt-3 text-center">
                        Pas de compte ? <a href="register.php">Créer un compte</a>
                    </p>
                </form>

            </div>
        </div>
    </div>
</div>
