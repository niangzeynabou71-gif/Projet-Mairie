<!DOCTYPE html>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="col-md-6 mx-auto shadow p-4">
            <h2 class="text-center mb-4">Demande d'extrait</h2>
            <form method="POST" action="">
                <input type="text" name="nom" class="form-control mb-2" placeholder="Nom" required>
                <input type="text" name="prenom" class="form-control mb-2" placeholder="Prénom" required>
                <input type="date" name="date" class="form-control mb-2" required>
                <select name="sexe" id="" class="form-control mb-2" required placeholder="sexe declaration">
                    <option value="" disabled selected>--Sexe--</option>
                    <option value="masculin">Masculin</option>
                    <option value="feminin">Féminin</option>
                </select>
                <input type="text" name="nom_pere" class="form-control mb-2" placeholder="Nom du père" required>
                <input type="text" name="nom_mere" class="form-control mb-2" placeholder="Nom de la mère" required>
                <select name="lieu" id="" class="form-control mb-2" required placeholder="mairie declaration">
                    <option value="" disabled selected>--Choisir mairie--</option>
                    <option value="sacre_coeur">Sacre-coeur</option>
                </select>
                <input type="email" name="email" class="form-control mb-2" placeholder="email" required>
                <input type="text" name="tel" class="form-control mb-2" placeholder="Téléphone" required>
                <button type="submit" name="submit" class="btn btn-primary">Envoyer</button>
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
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $nom_pere = trim($_POST['nom_pere'] ?? '');
    $nom_mere = trim($_POST['nom_mere'] ?? '');
    $lieu = trim($_POST['lieu'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $tel = trim($_POST['tel'] ?? '');
    $sexe = trim($_POST['sexe'] ?? '');
    $date_demande = date("d-m-Y");

    if (empty($nom) || empty($prenom) || empty($date) || empty($nom_pere) || empty($nom_mere) || empty($lieu) || empty($email) || empty($tel) || empty($sexe) || empty($date_demande)) {
        echo "Tous les champs sont requis.";
    } else {
        // Prepared statement - SQL Injection fix
        $stmt = $connect->prepare("INSERT INTO demande_extrait (nom, prenom, date_naissance, nom_pere, nom_mere, lieu_naissance, email, telephone, sexe, date_demande, statut) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'En attente')");
        $stmt->bind_param("ssssssssss", $nom, $prenom, $date, $nom_pere, $nom_mere, $lieu, $email, $tel, $sexe, $date_demande);

        if ($stmt->execute()) {
            // Sanitized file writes
            $safe_nom = htmlspecialchars($nom, ENT_QUOTES, 'UTF-8');
            $safe_prenom = htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8');

            // CSV
            $fichier_csv = fopen("demandes.csv", "a");
            fputcsv($fichier_csv, [$safe_nom, $safe_prenom, $date, $nom_pere, $nom_mere, $lieu, $email, $tel, $sexe, $date_demande]);
            fclose($fichier_csv);

            // TXT
            $fichier = fopen("demandes.txt", "a");
            fwrite($fichier, "$safe_nom $safe_prenom | $date | $nom_pere| $nom_mere | $lieu | $email | $tel | $sexe | $date_demande \n");
            fclose($fichier);

            echo "<div class='alert alert-success'>Demande envoyée avec succès!</div>";
            echo "<a href='accueil.php' class='btn btn-secondary'>Retour</a>";
        } else {
            echo "<div class='alert alert-danger'>Erreur : " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
}
?>