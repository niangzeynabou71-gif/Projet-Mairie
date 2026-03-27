<?php
session_start();
require_once("connexion.php");

$message = "";

// ================= INSCRIPTION =================
if (isset($_POST['register'])) {

    $numero_villa = trim($_POST['numero_villa']);
    $nom = trim($_POST['nom']);
    $password = $_POST['password'];

    if (empty($numero_villa) || empty($nom) || empty($password)) {
        $message = "<div class='alert alert-danger'>Tous les champs sont obligatoires</div>";
    } else {

        // Vérifier si utilisateur existe
        $check = mysqli_prepare($connect, "SELECT id FROM users WHERE numero_villa=?");
        mysqli_stmt_bind_param($check, "s", $numero_villa);
        mysqli_stmt_execute($check);
        $result = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($result) > 0) {
            $message = "<div class='alert alert-danger'>Ce numéro de villa est déjà utilisé</div>";
        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // INSERT CORRIGÉ
            $req = mysqli_prepare($connect, "INSERT INTO users (numero_villa, nom, password) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($req, "sss", $numero_villa, $nom, $hashedPassword);

            if (mysqli_stmt_execute($req)) {
                $message = "<div class='alert alert-success'>Compte créé avec succès</div>";
            } else {
                $message = "<div class='alert alert-danger'>Erreur lors de l'inscription</div>";
            }
        }
    }
}

// ================= CONNEXION =================
if (isset($_POST['login'])) {

    $numero_villa = trim($_POST['numero_villa']);
    $password = $_POST['password'];

    if (empty($numero_villa) || empty($password)) {
        $message = "<div class='alert alert-danger'>Veuillez remplir tous les champs</div>";
    } else {

        $req = mysqli_prepare($connect, "SELECT * FROM users WHERE numero_villa=?");
        mysqli_stmt_bind_param($req, "s", $numero_villa);
        mysqli_stmt_execute($req);

        $result = mysqli_stmt_get_result($req);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {

            // SESSION CORRIGÉE
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['role'] = $user['role']; // 'admin' ou 'user'
            $_SESSION['user'] = $user['numero_villa']; // Identifiant unique (Numéro villa)
            header("Location: accueil.php");
            exit();
        } else {
            $message = "<div class='alert alert-danger'>Identifiants incorrects</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Plateforme Mairie</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #1d2671, #c33764);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            width: 420px;
            padding: 30px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            color: white;
            text-align: center;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .btn-custom {
            border-radius: 10px;
            padding: 10px;
            font-weight: 500;
        }

        .btn-login {
            background: #0d6efd;
        }

        .btn-register {
            background: #198754;
        }

        .form-control {
            border-radius: 10px;
        }
    </style>
</head>

<body>

    <div class="card">

        <div class="title">Plateforme Mairie</div>

        <!-- MESSAGE -->
        <?php echo $message; ?>

        <!-- Boutons -->
        <div class="mb-3">
            <button class="btn btn-login btn-custom w-100 mb-2" onclick="showLogin()">Connexion</button>
            <button class="btn btn-register btn-custom w-100" onclick="showRegister()">Créer un compte</button>
        </div>

        <!-- LOGIN -->
        <form method="POST" id="loginForm" style="display:block;">
            <input type="text" name="numero_villa" class="form-control mb-2" placeholder="Numéro villa" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Mot de passe" required>
            <button type="submit" name="login" class="btn btn-login btn-custom w-100">Se connecter</button>
        </form>

        <!-- REGISTER -->
        <form method="POST" id="registerForm" style="display:none;">
            <input type="text" name="nom" class="form-control mb-2" placeholder="Nom complet" required>
            <input type="text" name="numero_villa" class="form-control mb-2" placeholder="Numéro villa" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Mot de passe" required>
            <button type="submit" name="register" class="btn btn-register btn-custom w-100">Créer un compte</button>
        </form>

    </div>


</body>

</html>