<?php
session_start();
require_once 'config.php';

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Rediriger si l'utilisateur n'est pas connecté ou n'est pas autorisé
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer les informations de l'utilisateur connecté
$sql = "SELECT type FROM utilisateurs WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Erreur de préparation de la requête : " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['type'] != 'administrateur' && $user['type'] != 'vendeur') {
    header('Location: account.php');
    exit;
}

$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $qualite = $_POST['qualite'];
    $defaut = $_POST['defaut'];
    $prix = $_POST['prix'];
    $id_categorie = $_POST['id_categorie'];
    $type_vente = $_POST['type_vente'];

    // Gérer l'upload des photos
    $photo_principale = '';
    if (isset($_FILES['photo_principale']) && $_FILES['photo_principale']['error'] == 0) {
        $photo_principale = 'uploads/' . basename($_FILES['photo_principale']['name']);
        if (!move_uploaded_file($_FILES['photo_principale']['tmp_name'], $photo_principale)) {
            die("Erreur lors de l'upload de la photo principale.");
        }
    } else {
        if (isset($_FILES['photo_principale']['error']) && $_FILES['photo_principale']['error'] != 0) {
            die("Erreur: " . $_FILES['photo_principale']['error']);
        }
        die("Erreur: la photo principale n'a pas été téléchargée.");
    }

    $sql = "INSERT INTO items (nom, description, qualite, defaut, prix, id_vendeur, id_categorie, en_vente, type_vente, photo_principale) VALUES (?, ?, ?, ?, ?, ?, ?, 'oui', ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erreur de préparation de la requête : " . $conn->error);
    }
    $stmt->bind_param("ssssdiiss", $nom, $description, $qualite, $defaut, $prix, $user_id, $id_categorie, $type_vente, $photo_principale);

    if ($stmt->execute()) {
        echo "Objet mis en ligne avec succès.";
    } else {
        die("Erreur lors de l'insertion dans la base de données : " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un objet - Agora Francia</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="wrapper">
        <header>
            <h1>Ajouter un objet</h1>
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
            <h2>Mettre en ligne un nouvel objet</h2>
            <form action="ajouter_objet.php" method="POST" enctype="multipart/form-data">
                <label for="nom">Nom de l'objet:</label>
                <input type="text" id="nom" name="nom" required>
                <br>
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
                <br>
                <label for="qualite">Qualité:</label>
                <input type="text" id="qualite" name="qualite" required>
                <br>
                <label for="defaut">Défaut:</label>
                <input type="text" id="defaut" name="defaut" required>
                <br>
                <label for="prix">Prix:</label>
                <input type="number" step="0.01" id="prix" name="prix" required>
                <br>
                <label for="id_categorie">Catégorie:</label>
                <select id="id_categorie" name="id_categorie" required>
                    <?php
                    // Récupérer les catégories de la base de données
                    $sql = "SELECT * FROM categories";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['id.objet'] . '">' . htmlspecialchars($row['nom']) . '</option>';
                        }
                    } else {
                        echo '<option value="">Aucune catégorie disponible</option>';
                    }
                    ?>
                </select>
                <br>
                <label for="type_vente">Type de vente:</label>
                <select id="type_vente" name="type_vente" required>
                    <option value="encheres">Enchères</option>
                    <option value="transaction">Transaction</option>
                    <option value="immediat">Achat immédiat</option>
                </select>
                <br>
                <label for="photo_principale">Photo principale:</label>
                <input type="file" id="photo_principale" name="photo_principale" accept="Images/*" required>
                <br>
                <button type="submit">Ajouter l'objet</button>
            </form>
        </section>
    </div>
</body>
</html>