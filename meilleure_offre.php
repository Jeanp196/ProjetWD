<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "agora_francia";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fonction pour récupérer les détails d'un article
function getArticleDetails($article_id) {
    global $conn;
    $sql = "SELECT * FROM articles WHERE id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $article_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $article = $result->fetch_assoc();
        $stmt->close();
        return $article;
    } else {
        die("Error preparing statement: " . $conn->error);
    }
}

// Fonction pour placer une enchère
function placeBid($article_id, $user_id, $max_bid) {
    global $conn;
    
    // Vérifier si l'enchère existe déjà pour cet utilisateur et cet article
    $sql = "SELECT * FROM encheres WHERE article_id = ? AND utilisateur_id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $article_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Mettre à jour l'enchère existante
            $sql = "UPDATE encheres SET prix_maximum = ?, date_enchere = NOW() WHERE article_id = ? AND utilisateur_id = ?";
        } else {
            // Insérer une nouvelle enchère
            $sql = "INSERT INTO encheres (article_id, utilisateur_id, prix_maximum, date_enchere) VALUES (?, ?, ?, NOW())";
        }
        
        $stmt->close();
        
        if ($stmt = $conn->prepare($sql)) {
            if ($result->num_rows > 0) {
                $stmt->bind_param("dii", $max_bid, $article_id, $user_id);
            } else {
                $stmt->bind_param("iid", $article_id, $user_id, $max_bid);
            }
            $stmt->execute();
            $stmt->close();
        } else {
            die("Error preparing statement: " . $conn->error);
        }
    } else {
        die("Error preparing statement: " . $conn->error);
    }
}

// Fonction pour obtenir l'enchère la plus élevée pour un article
function getHighestBid($article_id) {
    global $conn;
    $sql = "SELECT MAX(prix_maximum) as max_bid FROM encheres WHERE article_id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $article_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $bid = $result->fetch_assoc();
        $stmt->close();
        return $bid['max_bid'];
    } else {
        die("Error preparing statement: " . $conn->error);
    }
}

// Interface pour soumettre une enchère
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $article_id = intval($_POST['article_id']);
    $user_id = intval($_POST['user_id']);
    $max_bid = floatval($_POST['max_bid']);
    placeBid($article_id, $user_id, $max_bid);
    header("Location: meilleure_offre.php?article_id=$article_id");
    exit();
}

$article_id = isset($_GET['article_id']) ? intval($_GET['article_id']) : 0;
$article = getArticleDetails($article_id);
$highest_bid = getHighestBid($article_id);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Meilleure Offre</title>
</head>
<body>
    <h1>Meilleure Offre pour <?php echo htmlspecialchars($article['nom']); ?></h1>
    <p><?php echo htmlspecialchars($article['description']); ?></p>
    <p>Prix Initial: €<?php echo htmlspecialchars($article['prix_initial']); ?></p>
    <p>Prix Actuel: €<?php echo htmlspecialchars($highest_bid); ?></p>
    
    <form method="POST" action="meilleure_offre.php">
        <input type="hidden" name="article_id" value="<?php echo $article_id; ?>">
        <input type="hidden" name="user_id" value="1"> <!-- Remplacez par l'ID de l'utilisateur connecté -->
        <label for="max_bid">Votre enchère maximum:</label>
        <input type="number" name="max_bid" step="0.01" required>
        <button type="submit">Enchérir</button>
    </form>
</body>
</html>

<?php $conn->close(); ?>
