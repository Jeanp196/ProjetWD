<?php
session_start();
include 'db_connection.php'; // Fichier contenant les informations de connexion à la base de données

if (isset($_POST['ID_article']) && isset($_SESSION['pseudo_ach'])) {
  $ID_article = $_POST['ID_article'];
  $pseudo_ach = $_SESSION['pseudo_ach'];

  $conn = OpenCon(); // Fonction pour ouvrir la connexion à la base de données

  $sql = "INSERT INTO panier (pseudo_ach, ID_article) VALUES (?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $pseudo_ach, $ID_article);

  if ($stmt->execute()) {
    echo "Article ajouté au panier avec succès.";
  } else {
    echo "Erreur lors de l'ajout de l'article au panier: " . $stmt->error;
  }

  $stmt->close();
  CloseCon($conn); // Fonction pour fermer la connexion à la base de données
} else {
  echo "Erreur: L'utilisateur n'est pas connecté ou l'ID de l'article est manquant.";
}
?>
