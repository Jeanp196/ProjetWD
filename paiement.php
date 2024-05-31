<?php
session_start();
require_once 'config.php';

// Rediriger si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pseudo_ach = $_SESSION['user_id'];

// Récupérer le montant total du panier
$montant = $_SESSION['prixTotal'];

// Vérifier la connexion à la base de données
if (!$conn) {
    die("Erreur de connexion à la base de données: " . mysqli_connect_error());
}

// Récupérer les informations de l'acheteur
$sql = "SELECT * FROM acheteur WHERE pseudo_ach = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $pseudo_ach);
$stmt->execute();
$result = $stmt->get_result();
$acheteur = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement - Agora Francia</title>
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
            <h3>Montant Total du panier : <?php echo htmlspecialchars($montant); ?> €</h3>
            <h3>Informations de Livraison</h3>
            <p>Nom : <?php echo htmlspecialchars($acheteur['prenom_ach']) . " " . htmlspecialchars($acheteur['nom_ach']); ?></p>
            <p>Adresse : <?php echo htmlspecialchars($acheteur['rue']); ?></p>
            <p>Ville : <?php echo htmlspecialchars($acheteur['ville']); ?></p>
            <p>Code Postal : <?php echo htmlspecialchars($acheteur['codePostal']); ?></p>
            <p>Pays : <?php echo htmlspecialchars($acheteur['pays']); ?></p>
            <p>Numéro de téléphone : <?php echo htmlspecialchars($acheteur['numTel']); ?></p>

            <h3>Paiement</h3>
            <h4>Informations bancaires :</h4>
            <form method="post">
                <table>
                    <tr>
                        <td>
                            <label for="creditCard">Type de carte :</label><br>
                            <input type="radio" name="creditCard" value="MasterCard"> MasterCard<br>
                            <input type="radio" name="creditCard" value="Visa"> Visa<br>
                            <input type="radio" name="creditCard" value="Amex"> American Express<br>
                            <input type="radio" name="creditCard" value="PayPal"> PayPal<br>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="nomCarte">Nom sur la carte:</label>
                            <input type="text" id="nomCarte" name="nomCarte" maxlength="50">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="numCarte">Numéro de la carte :</label>
                            <input type="text" id="numCarte" name="numCarte" maxlength="16">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="expiration">Date d'expiration :</label>
                            <input type="date" id="expiration" name="expiration" maxlength="10">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="cryptogramme">Cryptogramme :</label>
                            <input type="text" id="cryptogramme" name="cryptogramme" maxlength="3">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button type='submit' name='paiement'>Valider</button>
                        </td>
                    </tr>
                </table>
            </form>
            <?php
            if (isset($_POST["paiement"])) {
                $typeCarte = isset($_POST["creditCard"]) ? $_POST["creditCard"] : "";
                $nomCarte = isset($_POST["nomCarte"]) ? $_POST["nomCarte"] : "";
                $numCarte = isset($_POST["numCarte"]) ? $_POST["numCarte"] : "";
                $expiration = isset($_POST["expiration"]) ? $_POST["expiration"] : NULL;
                $cryptogramme = isset($_POST["cryptogramme"]) ? $_POST["cryptogramme"] : "";

                // Vérifier si tous les champs sont remplis
                if (empty($typeCarte) || empty($nomCarte) || empty($numCarte) || empty($expiration) || empty($cryptogramme)) {
                    echo "Veuillez remplir tous les champs.<br>";
                } else {
                    // Vérifier les informations de la carte dans la base de données
                    $sql = "SELECT * FROM acheteur WHERE pseudo_ach = ? AND typeCarte = ? AND nomCarte = ? AND numCarte = ? AND expiration = ? AND cryptogramme = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssssss", $pseudo_ach, $typeCarte, $nomCarte, $numCarte, $expiration, $cryptogramme);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $solde = $row['solde'];

                        if ($solde >= $montant) {
                            // Mettre à jour le solde
                            $nouveau_solde = $solde - $montant;
                            $update_sql = "UPDATE acheteur SET solde = ? WHERE pseudo_ach = ?";
                            $update_stmt = $conn->prepare($update_sql);
                            $update_stmt->bind_param("ds", $nouveau_solde, $pseudo_ach);
                            $update_stmt->execute();

                            // Supprimer les articles du panier
                            $delete_sql = "DELETE FROM panier WHERE pseudo_ach = ?";
                            $delete_stmt = $conn->prepare($delete_sql);
                            $delete_stmt->bind_param("s", $pseudo_ach);
                            $delete_stmt->execute();

                            echo "Paiement accepté. Vous allez recevoir les informations de livraison par mail.<br>";
                        } else {
                            echo "Paiement refusé. Solde insuffisant.<br>";
                        }
                    } else {
                        echo "Vos informations bancaires sont incorrectes.<br>";
                    }

                    $stmt->close();
                }
            }

            $conn->close();
            ?>
        </section>
        <footer>
            &copy; 2024 Agora Francia. Tous droits réservés.
        </footer>
    </div>
</body>
</html>
