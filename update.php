<?php
include("connexion.php");

// Validate inputs
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$nom = trim($_POST['nom'] ?? '');
$prenom = trim($_POST['prenom'] ?? '');

if ($id <= 0 || empty($nom) || empty($prenom)) {
    header("Location: administration.php?error=empty");
    exit;
}

// Prepared UPDATE - SQL Injection fix
$stmt = $connect->prepare("UPDATE demande_extrait SET nom = ?, prenom = ? WHERE id = ?");
$stmt->bind_param("ssi", $nom, $prenom, $id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    header("Location: administration.php?updated=1");
} else {
    header("Location: administration.php?error=1");
}
$stmt->close();
