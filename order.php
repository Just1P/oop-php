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
        $this->status = "CART";
        $this->createdAt = new DateTime();
        $this->id = rand();

        $this->products = $products;
        $this->customerName = $customerName;
        $this->totalPrice = count($products) * 5;

        echo "Commande {$this->id} créée, d'un montant de {$this->totalPrice} !<br>";
    }

    public function maxQty(): void {
        if (count($this->products) > 5) {
            echo "Vous dépassez la limite de 5 produits<br>";
        }
    }

    public function blackList(): void {
        if ($this->customerName === "David Robert") {
            echo "Vous êtes banni de ce site<br>";
        }
    }
}

$order = new Order('David Robert', ['Casque', 'Téléphone']);
$order->maxQty();
$order->blackList();
