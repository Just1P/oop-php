<?php
require_once '../model/Order.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = $_POST['address'] ?? '';
    $country = $_POST['country'] ?? '';
    $shippingMethod = $_POST['shippingMethod'] ?? '';

    try {
        if (isset($_SESSION['order'])) {
            $order = $_SESSION['order'];
        } else {
            throw new Exception("Aucune commande trouvée dans la session.");
        }

        $order->setShippingAddress($address);
        $order->setShippingCountry($country);
        $order->setShippingMethod($shippingMethod);

        $_SESSION['order'] = $order;

        $details = $order->getDetails();
        $products = implode(', ', $details['products']);
        $totalPrice = $details['total'];

        echo "<h2>Détails de la commande</h2>";
        echo "<p>Nom du client : {$details['customer']}</p>";
        echo "<p>Produits : {$products}</p>";
        echo "<p>Adresse de livraison : {$address}</p>";
        echo "<p>Pays de livraison : {$country}</p>";
        echo "<p>Méthode de livraison : {$shippingMethod}</p>";
        echo "<p>Montant total : {$totalPrice}€</p>";

    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Méthode non autorisée.";
}
