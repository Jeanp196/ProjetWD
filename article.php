<?php
session_start();
include 'config.php';

$id_item = $_GET['id'];
$sql = "SELECT * FROM items WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_item);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo $item['nom']; ?></title>
</head>
<body>
<h1><?php echo $item['nom']; ?></h1>
<p><?php echo $item['description']; ?></p>
<p>Prix: €<?php echo $item['prix']; ?></p>

<?php if ($item['type_vente'] == 'encheres'): ?>
<form action="encherir.php" method="POST">
    <input type="hidden" name="id_item" value="<?php echo $item['id']; ?>">
    <input type="number" name="montant" placeholder="Votre enchère" required>
    <button type="submit">Enchérir</button>
</form>
<?php elseif ($item['type_vente'] == 'immediat'): ?>
<form action="achat_immediat.php" method="POST">
    <input type="hidden" name="id_item" value="<?php echo $item['id']; ?>">
    <button type="submit">Acheter maintenant</button>
</form>
<?php elseif ($item['type_vente'] == 'transaction'): ?>
<form action="transaction.php" method="POST">
    <input type="hidden" name="id_item" value="<?php echo $item['id']; ?>">
    <button type="submit">Contacter le vendeur</button>
</form>
<?php endif; ?>

</body>
</html>