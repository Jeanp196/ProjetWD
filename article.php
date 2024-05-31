<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    header('Location: tout_parcourir.php');
    exit;
}

$article_id = $_GET['id'];

// Récupérer les informations de l'article depuis la base de données
$sql = "SELECT * FROM items WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $article_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header('Location: tout_parcourir.php');
    exit;
}

$article = $result->fetch_assoc();

$stmt->close();
$conn->close();
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
            <h1><?php echo htmlspecialchars($article['nom']); ?></h1>
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
        </nav>
        <section class="main-section">
            <div class="article-details">
                <img src="<?php echo htmlspecialchars($article['photo_principale']); ?>" alt="<?php echo htmlspecialchars($article['nom']); ?>">
                <p><strong>Description:</strong> <?php echo htmlspecialchars($article['description']); ?></p>
                <p><strong>Qualité:</strong> <?php echo htmlspecialchars($article['qualite']); ?></p>
                <p><strong>Défaut:</strong> <?php echo htmlspecialchars($article['defaut']); ?></p>
                <p><strong>Prix:</strong> €<?php echo htmlspecialchars($article['prix']); ?></p>
                <p><strong>Type de vente:</strong> <?php echo htmlspecialchars($article['type_vente']); ?></p>
                <p><strong>Catégorie:</strong> <?php echo htmlspecialchars($article['id_categorie']); ?></p>
                <!-- Ajouter d'autres détails pertinents ici -->
                <button onclick="window.location.href='ajouter_au_panier.php?id=<?php echo $article['id']; ?>'">ajouter au panier</button>
            </div>
        </section>
        <footer>
            &copy; 2024 Agora Francia. Tous droits réservés.
        </footer>
    </div>
</body>
</html>
