<?php
session_start();
include("connexion.php");

// 🔒 Sécurité
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: accueil.php");
    exit();
}

// ================= FILTRE =================
$conditions = [];

if (!empty($_GET['statut'])) {
    $statut = $_GET['statut'];
    $conditions[] = "statut = '$statut'";
}

if (!empty($_GET['date'])) {
    $date = $_GET['date'];
    $conditions[] = "DATE(date_demande) = '$date'";
}

$sql = "SELECT * FROM demande_extrait";

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY id DESC";

$stmt = $connect->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// ================= ACTIONS =================
if (isset($_POST['valider_id'])) {
    $id = $_POST['valider_id'];
    $connect->query("UPDATE demande_extrait SET statut='validé' WHERE id=$id");
}

if (isset($_POST['rejeter_id'])) {
    $id = $_POST['rejeter_id'];
    $connect->query("UPDATE demande_extrait SET statut='rejeté' WHERE id=$id");
}

if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    $connect->query("DELETE FROM demande_extrait WHERE id=$id");
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Admin - Mairie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to right, #eef2f3, #dfe9f3);
        }

        .card {
            border-radius: 20px;
        }

        .badge {
            padding: 8px;
        }

        .header {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- HEADER -->
        <div class="header">
            <h2>🏛️ Administration des demandes</h2>
            <p>Filtrer, valider ou rejeter les demandes</p>
        </div>

        <!-- FILTRE -->
        <div class="card p-3 shadow mt-4">
            <form method="GET" class="row g-3">

                <div class="col-md-4">
                    <label>Filtrer par statut</label>
                    <select name="statut" class="form-control">
                        <option value="">-- Tous --</option>
                        <option value="En attente">En attente</option>
                        <option value="validé">Validé</option>
                        <option value="rejeté">Rejeté</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Filtrer par date</label>
                    <input type="date" name="date" class="form-control">
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Filtrer 🔍</button>
                </div>

            </form>
        </div>

        <!-- TABLEAU -->
        <div class="card shadow mt-4 p-3">
            <div class="table-responsive">

                <table class="table table-hover text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Date demande</th>
                            <th>Lieu</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>

                                <td><?= $row['id'] ?></td>
                                <td><?= $row['nom'] ?></td>
                                <td><?= $row['prenom'] ?></td>
                                <td><?= $row['date_demande'] ?></td>
                                <td><?= $row['lieu_naissance'] ?></td>

                                <td>
                                    <?php
                                    if ($row['statut'] == 'validé') {
                                        echo "<span class='badge bg-success'>Validé</span>";
                                    } elseif ($row['statut'] == 'rejeté') {
                                        echo "<span class='badge bg-danger'>Rejeté</span>";
                                    } else {
                                        echo "<span class='badge bg-warning'>En attente</span>";
                                    }
                                    ?>
                                </td>

                                <td>

                                    <!-- VALIDER -->
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="valider_id" value="<?= $row['id'] ?>">
                                        <button class="btn btn-success btn-sm">✔</button>
                                    </form>

                                    <!-- REJETER -->
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="rejeter_id" value="<?= $row['id'] ?>">
                                        <button class="btn btn-warning btn-sm">⚠</button>
                                    </form>

                                    <!-- SUPPRIMER -->
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                                        <button class="btn btn-danger btn-sm">✖</button>
                                    </form>

                                    <!-- PDF -->
                                    <a href="pdf.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">
                                        PDF
                                    </a>
                                    <!--email-->
                                    <a href="send_mail.php?id=<?= $row['id'] ?>" class="btn btn-secondary btn-sm">
                                        Email
                                    </a>
                                    <!--whatsapp-->
                                    <a href="send_whatsapp.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">
                                        WhatsApp </a>
                                </td>

                            </tr>
                        <?php endwhile; ?>

                    </tbody>
                </table>

            </div>

            <a href="accueil.php" class="btn btn-secondary mt-3">← Retour</a>

        </div>

    </div>

</body>

</html>

<?php $stmt->close(); ?>