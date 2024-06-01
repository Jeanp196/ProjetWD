<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'agora_francia';
$username = 'root'; // Remplacez par votre nom d'utilisateur
$password = 'root'; // Remplacez par votre mot de passe

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Impossible de se connecter à la base de données : " . $e->getMessage());
}

// Vérifier si l'ID de l'article est passé en paramètre
if (isset($_GET['id'])) {
    $id_objet = $_GET['id'];

    // Récupérer les détails de l'article
    $sql = "SELECT items.id_objet, items.nom, items.description, items.prix, items.type_vente, items.photo_principale, categories.nom AS categorie_nom
            FROM items
            JOIN categories ON items.id_categorie = categories.id
            WHERE items.id_objet = :id_objet";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_objet' => $id_objet]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si l'article n'existe pas
    if (!$article) {
        die("Article introuvable.");
    }
} else {
    die("ID de l'article non spécifié.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['nom']); ?> - Agora Francia</title>
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
            <div class="article-details">
                <h2><?php echo htmlspecialchars($article['nom']); ?></h2>
                <img src="<?php echo htmlspecialchars($article['photo_principale']); ?>" alt="<?php echo htmlspecialchars($article['nom']); ?>">
                <p><?php echo htmlspecialchars($article['description']); ?></p>
                <p>Prix: <?php echo htmlspecialchars($article['prix']); ?> €</p>
                <p>Type de vente: <?php echo htmlspecialchars($article['type_vente']); ?></p>
                <p>Catégorie: <?php echo htmlspecialchars($article['categorie_nom']); ?></p>
                <button onclick="window.location.href='ajouter_panier.php?id_item=<?php echo $article['id_objet']; ?>'">Ajouter au panier</button>
            </div>
        </section>
        <footer>
            &copy; 2024 Agora Francia. Tous droits réservés.
        </footer>
    </div>
</body>
</html>
