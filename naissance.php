<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Déclaration de naissance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #6b8dab, #519498);
        }

        .card {
            border-radius: 15px;
        }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-lg" style="width: 400px;">
            <h3 class="text-center mb-4">👶 Déclaration de naissance</h3>
            <form method="POST" action="">
                <input type="text" name="nom_bebe" class="form-control mb-3" placeholder="Nom du bébé" required>
                <input type="date" name="date" class="form-control mb-3" required>
                <select name="sexe" id="" class="form-control mb-2" required placeholder="sexe declaration">
                    <option value="" disabled selected>--Sexe--</option>
                    <option value="masculin">Masculin</option>
                    <option value="feminin">Féminin</option>
                </select>
                <input type="text" name="pere" class="form-control mb-3" placeholder="Nom du père" required>
                <input type="text" name="mere" class="form-control mb-3" placeholder="Nom de la mère" required>
                <select name="lieu" id="" class="form-control mb-2" required placeholder="mairie declaration">
                    <option value="" disabled selected>--Choisir mairie--</option>
                    <option value="sacre_coeur">Sacre-coeur</option>
                </select>
                <textarea name="adresse" class="form-control mb-3" placeholder="Adresse"></textarea>
                <button type="submit" name="submit" class="btn btn-success w-100">Déclarer</button>
            </form>
            <a href="accueil.php" class="btn btn-secondary mt-3">Retour</a>
        </div>


    </div>
</body>

</html>
<?php
include("connexion.php");

if (isset($_POST['submit'])) {
    // Sanitize inputs
    $nom_bebe = trim($_POST['nom_bebe'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $pere = trim($_POST['pere'] ?? '');
    $mere = trim($_POST['mere'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');

    if (empty($nom_bebe) || empty($date) || empty($pere) || empty($mere)) {
        echo "<div class='alert alert-warning text-center'>Champs obligatoires manquants.</div>";
    } else {
        // Prepared statement - SQL Injection fix
        $stmt = $connect->prepare("INSERT INTO naissance (nom_bebe, date_naissance, nom_pere, nom_mere, adresse) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nom_bebe, $date, $pere, $mere, $adresse);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success text-center'>Naissance enregistrée avec succès!</div>";
            echo "<div class='text-center'><a href='accueil.php' class='btn btn-secondary'>Retour</a></div>";
        } else {
            echo "<div class='alert alert-danger text-center'>Erreur : " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
}
?>