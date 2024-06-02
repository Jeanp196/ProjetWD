<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include './db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer et vérifier les valeurs postées
    if (isset($_POST['id_acheteur'], $_POST['id_article'], $_POST['enchere_max'])) {
        $id_acheteur = $_POST['id_acheteur'];
        $id_article = $_POST['id_article'];
        $enchere_max = $_POST['enchere_max'];

        // Préparer et exécuter la requête d'insertion
        $stmt = $conn->prepare("INSERT INTO encheres (id_acheteur, id_article, enchere_max) VALUES (?, ?, ?)");
        
        if ($stmt === false) {
            die("Erreur de préparation de la requête: " . $conn->error);
        }
        
        $stmt->bind_param("iid", $id_acheteur, $id_article, $enchere_max);
        
        if ($stmt->execute()) {
            echo "Votre enchère a été enregistrée avec succès!";
        } else {
            echo "Erreur: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Erreur: Veuillez remplir tous les champs du formulaire.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enchérir</title>
</head>
<body>
    <h1>Enchérir sur un article</h1>
    <form method="POST" action="enchere.php">
        <label for="id_acheteur">ID Acheteur:</label>
        <input type="number" id="id_acheteur" name="id_acheteur" required><br>
        <label for="id_article">ID Article:</label>
        <input type="number" id="id_item" name="id_item" required><br>
        <label for="enchere_max">Enchère Maximale (€):</label>
        <input type="number" id="enchere_max" name="enchere_max" step="0.01" required><br>
        <input type="submit" value="Enchérir">
    </form>
</body>
</html>
