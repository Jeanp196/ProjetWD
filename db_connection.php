

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Détails de la connexion à la base de données
$host = "localhost"; // Adresse du serveur de base de données
$db = "agora_francia"; // Nom de la base de données
$user = "root"; // Nom d'utilisateur de la base de données
$pass = "root"; // Mot de passe de la base de données

// Créer une connexion
$conn = new mysqli($host, $user, $pass, $db);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}
?>

