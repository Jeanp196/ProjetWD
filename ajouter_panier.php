<?php
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

// Vérifier si l'ID de l'article est passé en paramètre
if (isset($_GET['id_item'])) {
    $id_item = $_GET['id_item'];
    
    // Récupération de l'ID de l'utilisateur (remplacer par l'authentification réelle)
    $id_utilisateur = 1; // Changez ceci pour obtenir l'ID utilisateur authentifié
    
    // Vérifier si l'article est disponible pour achat immédiat
    $sql = "SELECT type_vente FROM items WHERE id_objet = :id_item";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_item' => $id_item]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['type_vente'] == 'immediat') {
        // Récupération de l'ID du panier de l'utilisateur
        $sql = "SELECT id FROM paniers WHERE id_acheteur = :id_acheteur";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_acheteur' => $id_utilisateur]);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $id_panier = $row['id'];
        } else {
            // Si le panier n'existe pas, le créer
            $sql = "INSERT INTO paniers (id_acheteur) VALUES (:id_acheteur)";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(['id_acheteur' => $id_utilisateur])) {
                $id_panier = $pdo->lastInsertId();
            } else {
                header("Location: panier.php?message=Erreur lors de la création du panier");
                exit();
            }
        }

        // Vérifier si l'article est déjà dans le panier
        $sql = "SELECT COUNT(*) FROM panier_items WHERE id_panier = :id_panier AND id_item = :id_item";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_panier' => $id_panier, 'id_item' => $id_item]);
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            // Ajouter l'article au panier
            $sql = "INSERT INTO panier_items (id_panier, id_item) VALUES (:id_panier, :id_item)";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(['id_panier' => $id_panier, 'id_item' => $id_item])) {
                header("Location: panier.php?message=Article ajouté au panier");
                exit();
            } else {
                header("Location: panier.php?message=Erreur lors de l'ajout de l'article au panier");
                exit();
            }
        } else {
            // Rediriger ou afficher un message si l'article est déjà dans le panier
            header("Location: panier.php?message=L'article est déjà dans le panier");
            exit();
        }
    } else {
        // Rediriger ou afficher un message si l'article n'est pas disponible pour achat immédiat
        header("Location: panier.php?message=Vous ne pouvez ajouter au panier que les articles en achat immédiat.");
        exit();
    }
} else {
    die("ID de l'article non spécifié.");
}
?>
