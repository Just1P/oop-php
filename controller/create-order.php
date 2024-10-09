<?php

require_once '../model/Order.php';

session_start();

try {

    if (!isset($_POST['customerName']) || !isset($_POST['products'])) {
        $errorMessage = "Merci de remplir les champs. J'ai pas fait tout ça pour rien.";
        require_once '../view/order-error.php';
        return;
    }

    $customerName = $_POST['customerName'];
    $products = $_POST['products'];

    $order = new Order($customerName, $products);

    persistOrder($order);


    require_once '../view/order-created.php';

} catch (Exception $e) {
    $errorMessage = $e->getMessage();
    require_once '../view/order-error.php';
}

function persistOrder(Order $order) {
    $_SESSION['order'] = $order;
}
