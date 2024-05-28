<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "agora";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $article_id = $_POST['article_id'];
    $user_id = $_POST['user_id'];
    $bid_amount = $_POST['bid_amount'];

    $sql = "SELECT MAX(bid_amount) AS highest_bid FROM bids WHERE article_id = $article_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $highest_bid = $row['highest_bid'];

        if ($bid_amount > $highest_bid) {
            $sql = "INSERT INTO bids (article_id, user_id, bid_amount) VALUES ($article_id, $user_id, $bid_amount)";

            if ($conn->query($sql) === TRUE) {
                echo "Votre enchère a été enregistrée avec succès.";
            } else {
                echo "Erreur: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Votre enchère doit être supérieure à l'enchère actuelle de " . $highest_bid . "€.";
        }
    }
}

$conn->close();
?>
