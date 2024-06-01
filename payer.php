<?php
// payer.php

// Connexion à la base de données
$host = 'localhost';
$dbname = 'agora_francia';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Impossible de se connecter à la base de données : " . $e->getMessage());
}

// Récupération de l'ID de l'utilisateur (remplacer par l'authentification réelle)
$id_utilisateur = 1; // Changez ceci pour obtenir l'ID utilisateur authentifié

// Récupération de l'ID du panier de l'utilisateur
$sql = "SELECT id FROM paniers WHERE id_acheteur = :id_acheteur";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id_acheteur' => $id_utilisateur]);

if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $id_panier = $row['id'];

    // Récupération des articles dans le panier
    $sql = "SELECT items.id_objet, items.nom, items.description, items.prix, items.photo_principale 
            FROM panier_items 
            JOIN items ON panier_items.id_item = items.id_objet 
            WHERE panier_items.id_panier = :id_panier";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_panier' => $id_panier]);
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calcul du total
    $total = 0;
    foreach ($articles as $article) {
        $total += $article['prix'];
    }
} else {
    $articles = [];
    header("Location: panier.php?message=Votre panier est vide.");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement - Agora Francia</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="wrapper">
        <header>
            <h1>Agora Francia</h1>
            <div class="logo">
                <div class="cart"></div>
                <div class="text">AGORA</div>
            </div>
        </header>
        <nav class="navigation">
            <button onclick="window.location.href='index.php'">Accueil</button>
            <button onclick="window.location.href='tout_parcourir.php'">Tout Parcourir</button>
            <button onclick="window.location.href='notifications.php'">Notifications</button>
            <button onclick="window.location.href='panier.php'">Panier</button>
            <button onclick="window.location.href='account.php'">Votre Compte</button>
        </nav>
        <section class="main-section">
            <h2>Récapitulatif de votre achat</h2>
            <div class="articles">
                <?php foreach ($articles as $article): ?>
                    <div class="article">
                        <img src="<?php echo htmlspecialchars($article['photo_principale']); ?>" alt="<?php echo htmlspecialchars($article['nom']); ?>">
                        <p><?php echo htmlspecialchars($article['nom']); ?></p>
                        <p><?php echo htmlspecialchars($article['description']); ?></p>
                        <p>Prix: <?php echo htmlspecialchars($article['prix']); ?> €</p>
                    </div>
                <?php endforeach; ?>
            </div>
            <h3>Total: <?php echo htmlspecialchars($total); ?> €</h3>
            <form method="post" action="confirmer_paiement.php">
                <h3>Informations personnelles</h3>
                <label>Nom: <input type="text" name="nom" required></label>
                <label>Prénom: <input type="text" name="prenom" required></label>
                <label>Pays: <input type="text" name="pays" value="FRANCE" required></label>
                <label>Adresse 1: <input type="text" name="adresse1" required></label>
                <label>Adresse 2: <input type="text" name="adresse2"></label>
                <label>Ville: <input type="text" name="ville" required></label>
                <label>Code postal: <input type="text" name="code_postal" required></label>
                <h3>Informations bancaires</h3>
                <label>Numéro de carte: <input type="text" name="num_carte" required></label>
                <label>Date d'expiration: 
                    <select name="mois_exp" required>
                        <option value="01">01</option>
                        <!-- Ajoutez les autres mois -->
                        <option value="12">12</option>
                    </select>
                    <select name="annee_exp" required>
                        <option value="2024">2024</option>
                        <!-- Ajoutez les autres années -->
                    </select>
                </label>
                <label>CVV: <input type="text" name="cvv" required></label>
                <button type="submit" name="payer">Acheter</button>
            </form>
        </section>
        <footer>
            &copy; 2024 Agora Francia. Tous droits réservés.
        </footer>
    </div>
</body>
</html>
