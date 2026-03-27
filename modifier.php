<?php
include("connexion.php");

// Validate ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die("ID invalide.");
}

// Prepared SELECT
$stmt = $connect->prepare("SELECT * FROM demande_extrait WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <title>Modifier Demande</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body class="bg-light">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h4>Modifier ID <?= $row['id'] ?></h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="update.php">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">

                                <div class="mb-3">
                                    <label class="form-label">Nom</label>
                                    <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($row['nom']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Prénom</label>
                                    <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($row['prenom']) ?>" required>
                                </div>

                                <button type="submit" class="btn btn-success">Sauvegarder</button>
                                <a href="accueil.php" class="btn btn-secondary">Annuler</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

    </html>
<?php
} else {
    echo "<div style='text-align:center;padding:50px;color:red;'>Enregistrement non trouvé.</div>";
    echo "<a href='dashboard_fixed.php'>Retour</a>";
}
$stmt->close();
?>