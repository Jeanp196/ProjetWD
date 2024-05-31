<?php
// Informations de connexion
$servername = "localhost";
$username = "root";
$password = "root"; // Mot de passe par défaut pour MAMP
$dbname = "agora_francia"; // Nom de votre base de données

// Créez une connexion
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Vérifiez la connexion
if (!$conn) {
    die("La connexion a échoué: " . mysqli_connect_error());
}
?>

