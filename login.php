<?php
session_start();
include 'config.php'; // Fichier contenant les informations de connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = $_POST['pseudo'];
    $mot_de_passe = $_POST['mot_de_passe'];
    
    $sql = "SELECT * FROM utilisateurs WHERE pseudo = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $pseudo);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($mot_de_passe, $user['mot_de_passe'])) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: account.php");
                exit();
            } else {
                $error = "Mot de passe incorrect.";
            }
        } else {
            $error = "Pseudo incorrect.";
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
    <title>Connexion</title>
</head>
<body>
    <h2>Connexion</h2>
    <form method="post" action="">
        <label for="pseudo">Pseudo:</label>
        <input type="text" id="pseudo" name="pseudo" required><br>
        <label for="mot_de_passe">Mot de passe:</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required><br>
        <button type="submit">Se connecter</button>
    </form>
    <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
    <p>Pas encore de compte? <a href="register.php">S'inscrire</a></p>
</body>
</html>

