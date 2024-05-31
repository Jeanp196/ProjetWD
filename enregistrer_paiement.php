<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "agora_francia";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}

// Récupérer les données du formulaire
$type_carte = $_POST['type_carte'];
$numero_carte = $_POST['numero_carte'];
$nom_carte = $_POST['nom_carte'];
$expiration_mois = $_POST['expiration_mois'];
$expiration_annee = $_POST['expiration_annee'];
$expiration_carte = $expiration_annee . '-' . $expiration_mois . '-01';
$code_securite = $_POST['code_securite'];
$id_utilisateur = 1; // Remplacez par l'ID utilisateur réel

// Vérifier si l'utilisateur existe
$sql_verif_utilisateur = "SELECT id FROM utilisateurs WHERE id = ?";
$stmt_verif_utilisateur = $conn->prepare($sql_verif_utilisateur);
$stmt_verif_utilisateur->bind_param("i", $id_utilisateur);
$stmt_verif_utilisateur->execute();
$result_verif_utilisateur = $stmt_verif_utilisateur->get_result();

if ($result_verif_utilisateur->num_rows > 0) {
    // L'utilisateur existe, procéder à l'insertion
    $sql = "INSERT INTO paiements (id_utilisateur, type_carte, num_carte, nom_carte, expiration_carte, code_securite, date_paiement) VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $id_utilisateur, $type_carte, $numero_carte, $nom_carte, $expiration_carte, $code_securite);

    if ($stmt->execute()) {
        echo "Les informations de paiement ont été enregistrées avec succès.";
    } else {
        echo "Erreur: " . $stmt->error;
    }

    $stmt->close();
} else {
    // L'utilisateur n'existe pas
    echo "Erreur: L'utilisateur n'existe pas.";
}

$stmt_verif_utilisateur->close();
$conn->close();
?>
