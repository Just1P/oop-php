<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Commande</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php

class Order {
    private array $products;
    private string $customerName;
    private float $totalPrice;
    private int $id;
    private DateTime $createdAt;
    private string $status;
    private ?string $shippingMethod = null;
    private ?string $shippingCity = null;
    private ?string $shippingAddress = null;
    private ?string $shippingCountry = null;

    public function __construct(string $customerName, array $products) {
        if (count($products) > 5) {
            throw new Exception('Vous ne pouvez pas commander plus de 5 produits');
        }

        if ($customerName === "David Robert") {
            throw new Exception('Vous êtes blacklisté');
        }

        $this->status = "CART";
        $this->createdAt = new DateTime();
        $this->id = rand();
        $this->products = $products;
        $this->customerName = $customerName;
        $this->totalPrice = count($products) * 5;

        $this->display("Commande {$this->id} créée, d'un montant de {$this->totalPrice} !", 'info');
    }

    private function display(string $message, string $type = 'info'): void {
        $types = [
            'success' => 'green',
            'warning' => 'orange',
            'error' => 'red',
            'info' => 'blue'
        ];
        $color = $types[$type] ?? 'black';

        echo "<div style='color: $color; background-color: #f0f0f0; padding: 10px; margin-bottom: 5px; border-radius: 5px;'>$message</div>";
    }

    public function addProduct(string $product): void {
        if ($this->status !== "CART") {
            $this->display("Vous ne pouvez pas ajouter de produit car la commande n'est pas en statut 'CART'.", 'error');
            return;
        }

        if (in_array($product, $this->products)) {
            $this->display("Le produit '$product' est déjà dans la commande.", 'warning');
            return;
        }

        if (count($this->products) >= 5) {
            $this->display("Vous ne pouvez pas ajouter plus de 5 produits.", 'error');
            return;
        }

        $this->products[] = $product;
        $this->totalPrice = count($this->products) * 5;

        $this->display("Le produit '$product' a été ajouté à la commande.", 'success');
    }

    public function removeProduct(string $product): void {
        $key = array_search($product, $this->products);

        if ($key !== false) {
            unset($this->products[$key]);
            $this->products = array_values($this->products);
            $this->totalPrice = count($this->products) * 5;

            $this->display("Le produit '$product' a été supprimé de la commande.", 'success');
        } else {
            $this->display("Le produit '$product' n'existe pas dans la commande.", 'warning');
        }
    }

    public function setShippingCountry(string $shippingCountry): void {
        $this->shippingCountry = $shippingCountry;

        if (!in_array($this->shippingCountry, ['France', 'Belgique', 'Luxembourg'])) {
            throw new Exception('La livraison n\'est possible qu\'en France, Belgique ou Luxembourg.');
        }

        $this->display("Pays de livraison sélectionné : {$shippingCountry}.", 'info');
    }

    public function setShippingAddress(string $address): void {
        if ($this->status !== "CART") {
            throw new Exception('Vous ne pouvez pas définir l\'adresse de livraison car la commande n\'est pas en statut "CART".');
        }
    
        $this->shippingAddress = $address;
        $this->status = 'SHIPPING_ADDRESS_SET';
        $this->display("Adresse de livraison définie : {$address}.", 'info');
    }

    public function setShippingMethod(string $shippingMethod): void {
        if ($this->status !== "SHIPPING_ADDRESS_SET") {
            throw new Exception('Vous ne pouvez pas définir la méthode de livraison avant d\'avoir renseigné l\'adresse.');
        }
    
        $validMethods = ['chronopost Express', 'point relais', 'domicile'];
        if (!in_array($shippingMethod, $validMethods)) {
            throw new Exception('La méthode de livraison doit être "chronopost Express", "point relais" ou "domicile".');
        }
    
        $this->shippingMethod = $shippingMethod;
        $this->status = 'SHIPPING_METHOD_SET';
    
        if ($shippingMethod === 'chronopost Express') {
            $this->totalPrice += 5; 
        }
    
        $this->display("Méthode de livraison sélectionnée : {$shippingMethod}.", 'info');
    }

    public function pay(): void {
        if (empty($this->shippingMethod)) {
            throw new Exception('Vous ne pouvez pas payer la commande sans avoir sélectionné une méthode de livraison.');
        }
        if ($this->shippingMethod === 'chronopost Express') {
            $this->display("Vous avez choisi chronopost Express pour 5€ en plus, votre commande vous revient donc à {$this->totalPrice}€", 'info');
        }
        
        $this->status = 'PAID';
        $this->display("La commande a été payée avec succès.", 'success');
    }

    public function listProducts(): void {
        $this->display("Liste des produits : " . implode(', ', $this->products), 'info');
    }
}

try {
    $order = new Order('Julien', ['feuille', 'stylo', 'trousse', 'ak-47']);
    $order->listProducts();

    $order->addProduct('cahier');
    $order->listProducts();
    $order->addProduct('stylo'); 
    $order->listProducts();
    
    $order->removeProduct('stylo');
    $order->listProducts();
    $order->addProduct('stylo'); 
    $order->listProducts();
} catch(Exception $error) {
    echo "<div class='error'>{$error->getMessage()}</div>";
}

try {
    $order->setShippingCountry('Allemagne');
} catch(Exception $error) {
    echo "<div class='error'>{$error->getMessage()}</div>";
}

try {
    $order->setShippingCountry('France');
    $order->setShippingAddress('123 rue de Paris, Paris');
} catch(Exception $error) {
    echo "<div class='error'>{$error->getMessage()}</div>";
}

try {
    $order->setShippingMethod('chronopost Express');
} catch(Exception $error) {
    echo "<div class='error'>{$error->getMessage()}</div>";
}

try {
    $order->setShippingAddress('123 rue de Paris, Paris');
    $order->setShippingMethod('domicile');
} catch(Exception $error) {
    echo "<div class='error'>{$error->getMessage()}</div>";
}

try {
    $order->pay();
} catch(Exception $error) {
    echo "<div class='error'>{$error->getMessage()}</div>";
}

try {
    $order->setShippingMethod('chronopost Express');
    $order->pay();
} catch(Exception $error) {
    echo "<div class='error'>{$error->getMessage()}</div>";
}
?>

</body>
</html>