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

        echo "<p>Adresse et méthode de livraison définies avec succès.</p>";
        echo '<a href="../view/order-details.php"><button type="button">Voir les détails de la commande</button></a>';

    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Méthode non autorisée.";
}
