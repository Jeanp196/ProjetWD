<?php
session_start();
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Connexion à la base de données et récupération des informations sur l'utilisateur
// Assurez-vous de sécuriser l'accès à la base de données en utilisant des requêtes préparées ou en validant les données

// Récupération des informations de l'utilisateur depuis la session
$user_id = $_SESSION['user_id'];
// Requête SQL pour récupérer les informations de l'utilisateur depuis la base de données

// Si vous utilisez une base de données, vous devrez insérer ici le code pour récupérer les informations de l'utilisateur

// Exemple de code pour récupérer les informations de l'utilisateur depuis la session
$user_info = array(
    'pseudo' => 'JohnDoe', // Récupérez le pseudo de la session ou de la base de données
    'email' => 'john@example.com' // Récupérez l'email de la session ou de la base de données
);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Compte</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="wrapper">
        <h2>Mon Compte</h2>
        <div>
            <p><strong>Pseudo:</strong> <?php echo $user_info['pseudo']; ?></p>
            <p><strong>Email:</strong> <?php echo $user_info['email']; ?></p>
            <!-- Ajoutez ici d'autres informations sur l'utilisateur que vous souhaitez afficher -->
        </div>
        <a href="logout.php">Se déconnecter</a>
    </div>
</body>
</html>
