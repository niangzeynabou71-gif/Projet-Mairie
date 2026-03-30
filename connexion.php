<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "mairie";

$connect = mysqli_connect($host, $user, $password, $dbname);

// Vérification connexion
if (!$connect) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

// Optionnel : gérer les accents (UTF-8)
mysqli_set_charset($connect, "utf8");
