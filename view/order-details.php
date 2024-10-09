<?php
require_once '../model/Order.php';

session_start();

if (isset($_SESSION['order'])) {
    $order = $_SESSION['order'];
    $details = $order->getDetails();
    $products = implode(', ', $details['products']);
    $totalPrice = $details['total'];
    $customerName = $details['customer'];
    $orderId = $details['id'];
} else {
    echo "<p>Aucune commande trouvée.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la commande</title>
</head>
<body>
    <header>
        <h1>Détails de la commande</h1>
    </header>
    <main>
        <p><strong>Numéro de commande :</strong> <?= htmlspecialchars($orderId) ?></p>
        <p><strong>Nom du client :</strong> <?= htmlspecialchars($customerName) ?></p>
        <p><strong>Produits :</strong> <?= htmlspecialchars($products) ?></p>
        <p><strong>Montant total :</strong> <?= htmlspecialchars($totalPrice) ?> €</p>
        <a href="../controller/index.php"><button type="button">Retour à l'accueil</button></a>
    </main>
</body>
</html>
