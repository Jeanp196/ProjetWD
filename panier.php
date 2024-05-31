<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['id_acheteur'])) {
  $pseudo_ach = $_SESSION['pseudo_ach'];

  $conn = OpenCon(); // Fonction pour ouvrir la connexion à la base de données

  // Récupérer les articles du panier de l'acheteur
  $sql = "SELECT a.*, p.date_ajout FROM panier p
          JOIN article a ON p.ID_article = a.ID_article
          WHERE p.pseudo_ach = ?
          ORDER BY p.date_ajout DESC";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $pseudo_ach);
  $stmt->execute();
  $result = $stmt->get_result();

  $transactions = [];
  $achatsImmediats = [];

  while ($row = $result->fetch_assoc()) {
    if ($row['type_vente'] == 'transaction') {
      $transactions[] = $row;
    } else {
      $achatsImmediats[] = $row;
    }
  }

  $stmt->close();
  CloseCon($conn); // Fonction pour fermer la connexion à la base de données
} else {
  echo "Erreur: L'utilisateur n'est pas connecté.";
  exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Panier</title>
</head>
<body>
  <h1>Votre Panier</h1>

  <h2>Transactions Vendeur-Client</h2>
  <?php if (!empty($transactions)): ?>
    <ul>
      <?php foreach ($transactions as $article): ?>
        <li>
          <h3><?php echo htmlspecialchars($article['nom']); ?></h3>
          <img src="<?php echo htmlspecialchars($article['photo']); ?>" alt="<?php echo htmlspecialchars($article['nom']); ?>">
          <p><?php echo htmlspecialchars($article['description']); ?></p>
          <p>Prix: <?php echo htmlspecialchars($article['prix']); ?> €</p>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p>Aucun article en transaction vendeur-client dans votre panier.</p>
  <?php endif; ?>

  <h2>Achats Immédiats</h2>
  <?php if (!empty($achatsImmediats)): ?>
    <ul>
      <?php foreach ($achatsImmediats as $article): ?>
        <li>
          <h3><?php echo htmlspecialchars($article['nom']); ?></h3>
          <img src="<?php echo htmlspecialchars($article['photo']); ?>" alt="<?php echo htmlspecialchars($article['nom']); ?>">
          <p><?php echo htmlspecialchars($article['description']); ?></p>
          <p>Prix: <?php echo htmlspecialchars($article['prix']); ?> €</p>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p>Aucun achat immédiat dans votre panier.</p>
  <?php endif; ?>
</body>
</html>
