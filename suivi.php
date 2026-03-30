<?php
session_start();
include("connexion.php");

// sécurité
if (!isset($_SESSION['user'])) {
    header("Location: accueil.php");
    exit();
}

$user_nom = $_SESSION['user'];

// requête pour récupérer les demandes de l'utilisateur avec le suivi
$req = mysqli_query($connect, "
    SELECT d.id, d.nom, d.date_demande,
           s.statut, s.date_suivi
    FROM demande_extrait d
    LEFT JOIN suivie s ON d.id = s.id_demande
    ORDER BY d.date_demande DESC
");

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Suivi de mes demandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <h2 class="mb-4 text-center">📄 Suivi de mes demandes</h2>

        <table class="table table-bordered table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Date demande</th>
                    <th>Statut</th>
                    <th>Date suivi</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                <?php while ($row = mysqli_fetch_assoc($req)) { ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['nom'] ?></td>
                        <td><?= $row['date_demande'] ?></td>

                        <td>
                            <?php
                            if ($row['statut'] == "Validée") {
                                echo "<span class='badge bg-success'>Validée</span>";
                            } elseif ($row['statut'] == "Rejetée") {
                                echo "<span class='badge bg-danger'>Rejetée</span>";
                            } else {
                                echo "<span class='badge bg-warning'>En attente</span>";
                            }
                            ?>
                        </td>

                        <td><?= $row['date_suivi'] ?? '---' ?></td>
                        <!-- ✅ Bouton PDF -->
                        <td>
                            <?php if ($row['statut'] == "Validée") { ?>
                                <a href="pdf.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">
                                    📥 Télécharger PDF
                                </a>
                            <?php } else { ?>
                                <span class="text-muted">Indisponible</span>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>
        <a href="accueil.php" class="btn btn-secondary mt-3">← Retour</a>
    </div>

</body>

</html>