<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php';

// Sélectionner toutes les enchères en cours qui se terminent bientôt
$sql = "SELECT * FROM encheres_en_cours WHERE date_fin < NOW() + INTERVAL 1 HOUR AND date_fin > NOW()";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $id_item = $row['id_item'];

    // Vérifier l'enchère automatique la plus élevée pour cet article
    $sql_auto = "SELECT * FROM enchere_auto WHERE id_article = ? ORDER BY enchere_max DESC LIMIT 1";
    $stmt_auto = $conn->prepare($sql_auto);
    $stmt_auto->bind_param("i", $id_item);
    $stmt_auto->execute();
    $result_auto = $stmt_auto->get_result();
    $enchere_auto = $result_auto->fetch_assoc();

    if ($enchere_auto) {
        // Vérifier l'enchère actuelle la plus élevée pour cet article
        $sql_max = "SELECT MAX(montant) AS max_enchere FROM encheres WHERE id_item = ?";
        $stmt_max = $conn->prepare($sql_max);
        $stmt_max->bind_param("i", $id_item);
        $stmt_max->execute();
        $result_max = $stmt_max->get_result();
        $enchere_max = $result_max->fetch_assoc()['max_enchere'];

        // Déterminer la nouvelle enchère à placer
        $nouvelle_enchere = $enchere_max + 1;
        if ($nouvelle_enchere <= $enchere_auto['enchere_max']) {
            // Placer la nouvelle enchère
            $sql_enchere = "INSERT INTO encheres (id_item, id_acheteur, montant, date_enchere) VALUES (?, ?, ?, NOW())";
            $stmt_enchere = $conn->prepare($sql_enchere);
            $stmt_enchere->bind_param("iid", $id_item, $enchere_auto['id_utilisateur'], $nouvelle_enchere);
            $stmt_enchere->execute();
        }
    }

    $stmt_auto->close();
    $stmt_max->close();
}

$conn->close();
?>
