<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agora Francia - Tout Parcourir</title>
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
            <button onclick="window.location.href='accueil.php'">Accueil</button>
            <button onclick="window.location.href='tout_parcourir.php'">Tout Parcourir</button>
            <button>Notifications</button>
            <button>Panier</button>
            <button>Votre Compte</button>
        </nav>
        <section class="main-section">
            <div class="intro">
                <h2>Tout Parcourir</h2>
                <p>Explorez toutes les catégories des articles en vente sur Agora Francia.</p>
            </div>
            <div class="categories">
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "root";
                $dbname = "agora";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT id, title, description, image, category FROM articles";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="category">';
                        echo '<div class="article">';
                        echo '<a href="article.php?article_id='.$row["id"].'">';
                        echo '<img src="'.$row["image"].'" alt="'.$row["title"].'">';
                        echo '</a>';
                        echo '<p>'.$row["title"].'</p>';
                        echo '<p>Enchère actuelle : <span id="current-bid-'.$row["id"].'">...</span></p>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "0 results";
                }

                $conn->close();
                ?>
            </div>
        </section>
        <footer>
            &copy; 2024 Agora Francia. Tous droits réservés.
        </footer>
    </div>
    <script>
        function updateHighestBid(articleId) {
            fetch(`get_highest_bid.php?article_id=${articleId}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById(`current-bid-${articleId}`).textContent = data + '€';
                })
                .catch(error => console.error('Error:', error));
        }

        document.addEventListener('DOMContentLoaded', function() {
            const articles = document.querySelectorAll('.article span[id^="current-bid-"]');
            articles.forEach(article => {
                const articleId = article.id.replace('current-bid-', '');
                updateHighestBid(articleId);
            });
            setInterval(() => {
                articles.forEach(article => {
                    const articleId = article.id.replace('current-bid-', '');
                    updateHighestBid(articleId);
                });
            }, 5000);
        });
    </script>
</body>
</html>
