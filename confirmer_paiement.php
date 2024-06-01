<?php
// confirmer_paiement.php

// Connexion à la base de données
$host = 'localhost';
$dbname = 'agora_francia';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Impossible de se connecter à la base de données : " . $e->getMessage());
}

// Récupération de l'ID de l'utilisateur (remplacer par l'authentification réelle)
$id_utilisateur = 1; // Changez ceci pour obtenir l'ID utilisateur authentifié

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payer'])) {
    // Validation et traitement du formulaire de paiement ici

    // Récupération de l'ID du panier de l'utilisateur
    $sql = "SELECT id FROM paniers WHERE id_acheteur = :id_acheteur";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_acheteur' => $id_utilisateur]);

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $id_panier = $row['id'];

        // Vider le panier après l'achat
        $sql = "DELETE FROM panier_items WHERE id_panier = :id_panier";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_panier' => $id_panier]);

        // Optionnel : Vous pouvez également supprimer le panier lui-même
        // $sql = "DELETE FROM paniers WHERE id = :id_panier";
        // $stmt = $pdo->prepare($sql);
        // $stmt->execute(['id_panier' => $id_panier]);

        header("Location: confirmation.php?message=Achat réussi !");
        exit;
    } else {
        header("Location: panier.php?message=Votre panier est vide.");
        exit;
    }
} else {
    header("Location: panier.php");
    exit;
}
?>
