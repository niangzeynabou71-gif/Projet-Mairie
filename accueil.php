<?php
session_start();
include("connexion.php");

// Redirection si non connecté
if (!isset($_SESSION['user_nom'])) {
    header("Location: index.php");
    exit();
}

$user_nom = $_SESSION['user_nom'];

// 🔹 Requête pour récupérer les demandes de l'utilisateur avec le suivi
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
    <title>Dashboard - Mairie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #eef2f3, #dfe9f3);
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar {
            background: linear-gradient(90deg, #1d2671, #c33764);
        }

        .navbar-brand {
            color: white;
            font-weight: bold;
        }

        .header {
            text-align: center;
            margin-top: 40px;
        }

        .header h1 {
            font-weight: bold;
        }

        .card {
            border: none;
            border-radius: 20px;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .btn {
            border-radius: 10px;
        }

        .logout-btn {
            border-radius: 20px;
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        .badge {
            padding: 8px;
            font-size: 0.9em;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg px-4">
        <span class="navbar-brand">🏛️ Mairie Sacré-Cœur</span>
        <div class="ms-auto">
            <span class="text-white me-3">👤 <?= $_SESSION['user_nom']; ?></span>
            <a href="deconnexion.php" class="btn btn-danger logout-btn">Déconnexion</a>
        </div>
    </nav>

    <!-- HEADER -->
    <div class="header">
        <h1>Bienvenue sur votre espace</h1>
        <p>Gérez vos demandes d'extrait de naissance facilement</p>
    </div>

    <!-- CARDS -->
    <div class="container mt-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card text-center p-4 shadow-sm">
                    <h5>📄 Nouvelle demande</h5>
                    <p>Faire une demande d'extrait</p>
                    <a href="demande.php" class="btn btn-primary w-100">Accéder</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-4 shadow-sm">
                    <h5>👶 Déclaration</h5>
                    <p>Déclarer un nouveau-né</p>
                    <a href="naissance.php" class="btn btn-success w-100">Accéder</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-4 shadow-sm">
                    <h5>⚙️ Administration</h5>
                    <p>Gestion des demandes</p>
                    <a href="administration.php" class="btn btn-dark w-100">Admin</a>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLEAU DE SUIVI -->
    <div class="container mt-5">
        <div class="card p-4 shadow">
            <h4 class="mb-4">📊 Suivi de mes demandes</h4>
            <table class="table table-hover table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID Demande</th>
                        <th>Nom</th>
                        <th>Date Demande</th>
                        <th>Date Suivi</th>
                        <th>Statut</th>
                        <th>PDF</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($query) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['nom'] ?></td>
                                <td><?= $row['date_demande'] ?></td>
                                <td><?= $row['date_suivi'] ?? '—' ?></td>
                                <td>
                                    <?php
                                    $statut = $row['suivi_statut'] ?? 'En attente';
                                    if ($statut == "En attente") echo "<span class='badge bg-warning'>En attente</span>";
                                    elseif ($statut == "Validée") echo "<span class='badge bg-success'>Validée</span>";
                                    else echo "<span class='badge bg-danger'>Rejetée</span>";
                                    ?>
                                </td>
                                <td>
                                    <?php if ($statut == "Validée"): ?>
                                        <a href="pdf.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Télécharger</a>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm" disabled>Indisponible</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Aucune demande trouvée</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>