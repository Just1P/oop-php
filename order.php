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

		echo "Commande {$this->id} créée, d'un montant de {$this->totalPrice} !";
	}

}

$order = new Order('David Robert', ['Casque', 'Téléphone']);


