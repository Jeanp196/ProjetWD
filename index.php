<?php
// Inclure le fichier de configuration
require_once 'config.php';

// Récupérer les articles en vente de la base de données
$sql = "SELECT * FROM items WHERE en_vente = 1";
$result = $conn->query($sql);

// Fermer la connexion à la base de données
$conn->close();
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
        <section class="main-section">
            <div class="intro">
                <h2>Bienvenue sur Agora Francia</h2>
                <p>Découvrez notre plateforme de vente en ligne où vous pouvez acheter, négocier, et enchérir sur une variété d'articles.</p>
            </div>
            <div class="selection-du-jour">
                <h2>Sélection du jour</h2>
                <div class="carousel">
                    <?php
                    // Afficher les images des articles en vente dans le carousel
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<div><img src="' . $row['photo_principale'] . '" alt="' . $row['nom'] . '"></div>';
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="contact">
                <h2>Contactez-nous</h2>
                <p>Email: contact@agorafrancia.com</p>
                <p>Téléphone: +33 1 23 45 67 89</p>
                <p>Adresse: 10 rue sextus michel, Paris, France</p>
            </div>
            <div id="map"></div>
        </section>
        <footer>
            &copy; 2024 Agora Francia. Tous droits réservés.
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        $(document).ready(function(){
            $('.carousel').slick({
                dots: true,
                infinite: true,
                speed: 500,
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
            });
        });

        // Initialize the Leaflet map
        var map = L.map('map').setView([48.8566, 2.3522], 13);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Add a marker to the map
        L.marker([48.85111618041992, 2.2886924743652344]).addTo(map)
            .bindPopup('Agora Francia')
            .openPopup();
    </script>
</body>
</html>
