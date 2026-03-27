<?php
include("connexion.php");

if (!isset($_GET['id'])) {
    die("ID manquant");
}

$id = intval($_GET['id']);

// Récupération des données
$result = mysqli_query($connect, "SELECT * FROM demande_extrait WHERE id=$id");
$row = mysqli_fetch_assoc($result);

if (!$row) {
    die("Demande introuvable");
}

// Numéro WhatsApp (sans +)
$numero = "221771234567";

// Message
$text = "Bonjour, je veux faire une demande d'extrait:\n";
$text .= "Nom: " . $row['nom'] . "\n";
$text .= "Prénom: " . $row['prenom'] . "\n";
$text .= "Date de naissance: " . $row['date_naissance'] . "\n";
$text .= "Lieu: " . $row['lieu_naissance'];

// Redirection WhatsApp
header("Location: https://wa.me/$numero?text=" . urlencode($text));
exit();
