<?php
$servername = "localhost";
$username = "root";
$password = "root"; // Assurez-vous que cela correspond au mot de passe défini pour MAMP
$dbname = "agora_francia"; // Assurez-vous que le nom de la base de données est correct

// Créez une connexion
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Vérifiez la connexion
if (!$conn) {
    die("La connexion a échoué: " . mysqli_connect_error());
}
echo "Connexion réussie!";
?>
