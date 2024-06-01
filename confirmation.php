<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation - Agora Francia</title>
    <link rel="stylesheet" href="styles.css">
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
            <button onclick="window.location.href='tout_parcourir.php'">Tout Parcourir</button>
            <button onclick="window.location.href='notifications.php'">Notifications</button>
            <button onclick="window.location.href='panier.php'">Panier</button>
            <button onclick="window.location.href='account.php'">Votre Compte</button>
        </nav>
        <section class="main-section">
            <h2>Confirmation d'achat</h2>
            <?php if (isset($_GET['message'])): ?>
                <p><?php echo htmlspecialchars($_GET['message']); ?></p>
            <?php else: ?>
                <p>Votre achat a été réalisé avec succès.</p>
            <?php endif; ?>
            <button onclick="window.location.href='index.php'">Retour à l'accueil</button>
        </section>
        <footer>
            &copy; 2024 Agora Francia. Tous droits réservés.
        </footer>
    </div>
</body>
</html>
