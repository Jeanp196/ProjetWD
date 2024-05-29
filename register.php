<?php
session_start();
include 'config.php'; // Fichier contenant les informations de connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $courriel = $_POST['courriel'];
    $pseudo = $_POST['pseudo'];
    $nom = $_POST['nom'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $type = 'acheteur'; // Par défaut, l'utilisateur est un acheteur

    $sql = "INSERT INTO utilisateurs (courriel, pseudo, nom, mot_de_passe, type) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssss", $courriel, $pseudo, $nom, $mot_de_passe, $type);
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            header("Location: account.php");
            exit();
        } else {
            $error = "Erreur lors de l'inscription.";
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>
    <h2>Inscription</h2>
    <form method="post" action="">
        <label for="courriel">Email:</label>
        <input type="email" id="courriel" name="courriel" required><br>
        <label for="pseudo">Pseudo:</label>
        <input type="text" id="pseudo" name="pseudo" required><br>
        <label for="nom">Nom complet:</label>
        <input type="text" id="nom" name="nom" required><br>
        <label for="mot_de_passe">Mot de passe:</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required><br>
        <button type="submit">S'inscrire</button>
    </form>
    <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
    <p>Vous avez déjà un compte? <a href="login.php">Se connecter</a></p>
</body>
</html>
