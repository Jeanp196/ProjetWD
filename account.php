<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM utilisateurs WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $profile_pic = $_FILES['profile_pic']['name'];
    $background_pic = $_FILES['background_pic']['name'];

    if ($profile_pic) {
        $profile_pic_target = "uploads/" . basename($profile_pic);
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profile_pic_target);
    } else {
        $profile_pic_target = $user['photo_profil'];
    }

    if ($background_pic) {
        $background_pic_target = "uploads/" . basename($background_pic);
        move_uploaded_file($_FILES['background_pic']['tmp_name'], $background_pic_target);
    } else {
        $background_pic_target = $user['image_fond'];
    }

    $sql = "UPDATE utilisateurs SET photo_profil = ?, image_fond = ? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssi", $profile_pic_target, $background_pic_target, $user_id);
        if ($stmt->execute()) {
            header("Location: account.php");
            exit();
        } else {
            $error = "Erreur lors de la mise à jour.";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agora Francia</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
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
            <a href="tout_parcourir.php"><button>Tout Parcourir</button></a>
            <button onclick="window.location.href='notifications.php'">Notifications</button>
            <button>Panier</button>
            <button onclick="window.location.href='account.php'">Votre Compte</button>
        </nav>
<body>

    <div class="container">
        <h2>Mon Compte</h2>
        <div class="profile-section">
            <div>
                <h3>Profil de <?php echo htmlspecialchars($user['pseudo']); ?></h3>
                <img src="<?php echo htmlspecialchars($user['photo_profil'] ? $user['photo_profil'] : 'uploads/default_profile.png'); ?>" alt="Photo de profil">
                <img src="<?php echo htmlspecialchars($user['image_fond'] ? $user['image_fond'] : 'default_background.png'); ?>" alt="Image de fond" style="width: 100%; margin-top: 10px;">
            </div>
            <form method="post" enctype="multipart/form-data">
                <label for="profile_pic">Changer la photo de profil:</label><br>
                <input type="file" id="profile_pic" name="profile_pic"><br>
                <label for="background_pic">Changer l'image de fond:</label><br>
                <input type="file" id="background_pic" name="background_pic"><br>
                <button type="submit">Mettre à jour</button>
            </form>
        </div>
        <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
    </div>
</body>
</html>
