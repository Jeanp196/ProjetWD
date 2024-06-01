<?php
session_start();
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Traitement du formulaire de recherche
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des critères de recherche
    $search_criteria = $_POST['search_criteria'];
    
    // Traitement des critères de recherche (vous devez implémenter cette logique)
    
    // Redirection vers une page de résultats de recherche
    header("Location: search_results.php?criteria=" . urlencode($search_criteria));
    exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Notifications</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="wrapper">
        <header>
            <h1>Notifications</h1>
        </header>
        <nav class="navigation">
            <button onclick="window.location.href='index.php'">Accueil</button>
            <a href="tout_parcourir.php"><button>Tout Parcourir</button></a>
            <button onclick="window.location.href='notifications.php'">Notifications</button>
            <button onclick="window.location.href='panier.php'">Panier</button>
            <button onclick="window.location.href='account.php'">Votre Compte</button>
            <button onclick="window.location.href='logout.php'">Se Déconnecter</button>
        </nav>
        <section class="main-section">
            <h2>Notifications</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="search_criteria">Critères de recherche :</label>
                <input type="text" id="search_criteria" name="search_criteria" placeholder="Entrez vos critères de recherche">
                <button type="submit">Rechercher</button>
            </form>
            <!-- Afficher les notifications de l'utilisateur si vous en avez -->
            <!-- Vous pouvez ajouter ici d'autres éléments de notification ou d'alerte -->
        </section>
        <footer>
            &copy; 2024 Agora Francia. Tous droits réservés.
        </footer>
    </div>
</body>
</html>
