<?php

session_start();

require('fpdf186/fpdf.php');
require('connexion.php');
// header('location: accueil.php'); // Supprimé pour permettre la génération du PDF

// 🔐 Sécurité
if (!isset($_SESSION['user']) || !isset($_GET['id'])) {
    die("Accès non autorisé ou ID manquant");
}

$id = intval($_GET['id']);

// 🔍 Requête sécurisée
$stmt = mysqli_prepare($connect, "SELECT * FROM demande_extrait WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$data) {
    die("Aucune donnée trouvée");
}

// ================= PDF =================
class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 5, 'REPUBLIQUE DU SENEGAL', 0, 1, 'C');

        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 5, 'Un Peuple - Un But - Une Foi', 0, 1, 'C');

        $this->Ln(5);

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 5, 'MAIRIE DE SACRE-COEUR', 0, 1, 'C');

        $this->Ln(10);

        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'EXTRAIT DE NAISSANCE', 0, 1, 'C');

        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-30);
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 5, 'Fait a Dakar, le ' . date('d/m/Y'), 0, 1, 'R');

        $this->Ln(10);

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 5, 'Signature & Cachet', 0, 1, 'R');
    }
}

// 🎯 Création PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Numéro
$pdf->Cell(0, 10, 'Numero : ' . $data['id'], 0, 1);
$pdf->Ln(5);

// Tableau
function ligne($pdf, $label, $valeur)
{
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(90, 10, $label, 1, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(90, 10, $valeur, 1, 1);
}

ligne($pdf, 'Nom :', $data['nom']);
ligne($pdf, 'Prenom :', $data['prenom']);
ligne($pdf, 'Date de naissance :', $data['date_naissance']);
ligne($pdf, 'Lieu de naissance :', $data['lieu_naissance']);
ligne($pdf, 'Sexe :', $data['sexe']);
ligne($pdf, 'Nom du pere :', $data['nom_pere']);
ligne($pdf, 'Nom de la mere :', $data['nom_mere']);
// 📥 Télécharger direct
$pdf->Output('D', 'extrait_' . $data['id'] . '.pdf');
exit;
