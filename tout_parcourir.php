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

// Récupérer les articles mis en vente avec leurs catégories et types de vente
$sql = "SELECT items.id_objet, items.nom, items.description, items.prix, items.type_vente, items.photo_principale, categories.nom AS categorie_nom 
        FROM items 
        JOIN categories ON items.id_categorie = categories.id
        WHERE items.en_vente = 'oui'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organiser les articles par catégorie
$categories = [];
foreach ($articles as $article) {
    $categories[$article['categorie_nom']][] = $article;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agora Francia - Tout Parcourir</title>
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
            <div class="intro">
                <h2>Tout Parcourir</h2>
                <p>Explorez toutes les catégories des articles en vente sur Agora Francia.</p>
            </div>
            <div class="categories">
                <?php if (count($categories) > 0): ?>
                    <?php foreach ($categories as $categorie_nom => $articles): ?>
                        <div class="category">
                            <h3><?php echo htmlspecialchars($categorie_nom); ?></h3>
                            <div class="articles-row">
                                <?php foreach ($articles as $article): ?>
                                    <div class="article">
                                        <img src="<?php echo htmlspecialchars($article['photo_principale']); ?>" alt="<?php echo htmlspecialchars($article['nom']); ?>">
                                        <p><?php echo htmlspecialchars($article['nom']); ?></p>
                                        <p><?php echo htmlspecialchars($article['description']); ?></p>
                                        <p><?php echo htmlspecialchars($article['prix']); ?> €</p>
                                        <p>Type de vente : <?php echo htmlspecialchars($article['type_vente']); ?></p>
                                        <a href="article.php?id=<?php echo $article['id_objet']; ?>">Voir Détails</a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun article en vente pour le moment.</p>
                <?php endif; ?>
            </div>
        </section>
        <footer>
            &copy; 2024 Agora Francia. Tous droits réservés.
        </footer>
    </div>
</body>
</html>