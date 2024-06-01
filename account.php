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

// Récupérer les informations de paiement de l'utilisateur connecté
$sql_payment = "SELECT * FROM paiements WHERE id_utilisateur = ?";
$stmt_payment = $conn->prepare($sql_payment);
$stmt_payment->bind_param("i", $user_id);
$stmt_payment->execute();
$result_payment = $stmt_payment->get_result();
$payment = $result_payment->fetch_assoc();

$stmt->close();
$stmt_payment->close();
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
            <button onclick="window.location.href='panier.php'">Panier</button>
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

            <!-- Formulaire pour mettre à jour les informations de paiement -->
            <h3>Informations de paiement</h3>
            <form action="update_payment.php" method="post">
                <label for="type_carte">Type de carte:</label>
                <input type="text" id="type_carte" name="type_carte" value="<?php echo htmlspecialchars($payment['type_carte'] ?? ''); ?>" required><br>

                <label for="num_carte">Numéro de carte:</label>
                <input type="text" id="num_carte" name="num_carte" value="<?php echo htmlspecialchars($payment['num_carte'] ?? ''); ?>" required><br>

                <label for="nom_carte">Nom sur la carte:</label>
                <input type="text" id="nom_carte" name="nom_carte" value="<?php echo htmlspecialchars($payment['nom_carte'] ?? ''); ?>" required><br>

                <label for="expiration_carte">Date d'expiration:</label>
                <input type="date" id="expiration_carte" name="expiration_carte" value="<?php echo htmlspecialchars($payment['expiration_carte'] ?? ''); ?>" required><br>

                <label for="code_securite">Code de sécurité:</label>
                <input type="text" id="code_securite" name="code_securite" value="<?php echo htmlspecialchars($payment['code_securite'] ?? ''); ?>" required><br>

                <button type="submit">Mettre à jour les informations de paiement</button>
            </form>
        </section>
        <footer>
            &copy; 2024 Agora Francia. Tous droits réservés.
        </footer>
    </div>
</body>
</html>
