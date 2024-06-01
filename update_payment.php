<?php
session_start();
require_once 'config.php';

// Rediriger si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer les données du formulaire
$type_carte = $_POST['type_carte'];
$num_carte = $_POST['num_carte'];
$nom_carte = $_POST['nom_carte'];
$expiration_carte = $_POST['expiration_carte'];
$code_securite = $_POST['code_securite'];

// Vérifier si l'utilisateur a déjà des informations de paiement
$sql = "SELECT * FROM paiements WHERE id_utilisateur = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$payment_exists = $result->num_rows > 0;
$stmt->close();

if ($payment_exists) {
    // Mettre à jour les informations de paiement existantes
    $sql = "UPDATE paiements SET type_carte = ?, num_carte = ?, nom_carte = ?, expiration_carte = ?, code_securite = ? WHERE id_utilisateur = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $type_carte, $num_carte, $nom_carte, $expiration_carte, $code_securite, $user_id);
} else {
    // Insérer de nouvelles informations de paiement
    $sql = "INSERT INTO paiements (id_utilisateur, type_carte, num_carte, nom_carte, expiration_carte, code_securite, date_paiement) VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $user_id, $type_carte, $num_carte, $nom_carte, $expiration_carte, $code_securite);
}

if ($stmt->execute()) {
    // Rediriger vers la page de compte avec un message de succès
    $_SESSION['message'] = 'Informations de paiement mises à jour avec succès';
} else {
    // Rediriger vers la page de compte avec un message d'erreur
    $_SESSION['message'] = 'Erreur lors de la mise à jour des informations de paiement';
}

$stmt->close();
$conn->close();

header('Location: account.php');
exit;
?>
