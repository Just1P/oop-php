<?php

require_once '../model/Order.php';

try {
	$order = new Order('Jean Pierre', ['iphone', 'chaise']);
	
	echo "<html><body>Commande créée</body></html>";

} catch (Exception $e) {

	echo "<html><body><p>" . $e->getMessage() . "</p></body></html>";
}