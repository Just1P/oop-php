<?php

class Order {
    private array $products;
    private string $customerName;
    private float $totalPrice;
    private int $id;
    private DateTime $createdAt;
    private string $status;
    private ?string $shippingMethod;
    private ?string $shippingAddress;

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

        echo "Commande {$this->id} créée, d'un montant de {$this->totalPrice} !<br>";
    }

    public function removeProduct(string $productName): void {
        $key = array_search($productName, $this->products);

        if ($key !== false) {
            unset($this->products[$key]);
            $this->products = array_values($this->products);
            $this->totalPrice = count($this->products) * 5;

            echo "Le produit '$productName' a été supprimé de la commande.<br>";
        } else {
            echo "Le produit '$productName' n'existe pas dans la commande.<br>";
        }
    }

    public function listProducts(): void {
        echo "Liste des produits : " . implode(', ', $this->products) . "<br>";
    }
}

try {
    $order = new Order('Julien', ['feuille', 'stylo', 'trousse', 'ak-47']);
    $order->listProducts();
    $order->removeProduct('stylo');
    $order->listProducts();
    $order->removeProduct('cahier');
} catch(Exception $error) {
    echo $error->getMessage();
}
