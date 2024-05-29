<?php
session_start();
require_once 'config.php';

// Rediriger si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer les informations de l'utilisateur connecté
$sql = "SELECT * FROM utilisateurs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte - Agora Francia</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="wrapper">
        <header>
            <h1>Mon Compte</h1>
            <div class="logo">
                <div class="cart"></div>
                <div class="text">AGORA</div>
            </div>
        </header>
        <nav class="navigation">
            <button onclick="window.location.href='index.php'">Accueil</button>
            <a href="tout_parcourir.php"><button>Tout Parcourir</button></a>
            <button onclick="window.location.href='notifications.php'">Notifications</button>
            <button>Panier</button>
            <button onclick="window.location.href='account.php'">Votre Compte</button>
            <button onclick="window.location.href='logout.php'">Se Déconnecter</button>
        </nav>
        <section class="main-section">
            <h2>Bienvenue, <?php echo htmlspecialchars($user['pseudo']); ?>!</h2>
            <div class="profile-info">
                <img src="<?php echo htmlspecialchars($user['photo_profil'] ? $user['photo_profil'] : 'default_profile.png'); ?>" alt="Photo de profil">
                <img src="<?php echo htmlspecialchars($user['image_fond'] ? $user['image_fond'] : 'default_background.png'); ?>" alt="Image de fond" style="width: 100%; margin-top: 10px;">
                <p>Nom: <?php echo htmlspecialchars($user['nom']); ?></p>
                <p>Courriel: <?php echo htmlspecialchars($user['courriel']); ?></p>
                <p>Type de compte: <?php echo htmlspecialchars($user['type']); ?></p>
            </div>
            <?php if ($user['type'] == 'administrateur' || $user['type'] == 'vendeur') : ?>
                <button onclick="window.location.href='ajouter_objet.php'">Mettre en ligne un objet</button>
            <?php endif; ?>
        </section>
        <footer>
            &copy; 2024 Agora Francia. Tous droits réservés.
        </footer>
    </div>
</body>
</html>
