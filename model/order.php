<?php

class Order {
    // Constantes
    public const STATUS_CART = "CART";
    public const STATUS_SHIPPING_ADDRESS_SET = "SHIPPING_ADDRESS_SET";
    public const STATUS_SHIPPING_METHOD_SET = "SHIPPING_METHOD_SET";
    public const STATUS_PAID = "PAID";
    
    public const MESSAGE_SUCCESS = 'success';
    public const MESSAGE_WARNING = 'warning';
    public const MESSAGE_ERROR = 'error';
    public const MESSAGE_INFO = 'info';

    public static $MAX_PRODUCTS = 5;
    public static $BLACKLISTED_CUSTOMERS = ['David Robert'];
    public static $PRODUCT_PRICE = 5;
    public static $AUTHORIZED_COUNTRIES = ['France', 'Belgique', 'Luxembourg'];
    public static $SHIPPING_METHODS = ['Chronopost Express', 'Point relais', 'Domicile'];
    public static $EXPRESS_METHOD = 'Chronopost Express';
    public static $EXPRESS_COST = 5;

    private array $products = [];
    private string $customerName;
    private float $totalPrice = 0.0;
    private string $id;
    private DateTime $createdAt;
    private string $status;
    private ?string $shippingMethod = null;
    private ?string $shippingAddress = null;
    private ?string $shippingCountry = null;
    private array $messages = [];

    public function __construct(string $customerName, array $products) {
        $this->validateCustomer($customerName);
        $this->validateProductCount($products);

        $this->status = self::STATUS_CART;
        $this->createdAt = new DateTime();
        $this->id = uniqid('order_', true);
        $this->products = $products;
        $this->customerName = $customerName;
        $this->updateTotalPrice();

        echo "Commande {$this->id} créée, d'un montant de {$this->totalPrice} €!</br></br>";
    }

    private function isValidString(string $str): bool {
        $str = trim($str); // Supprime les espaces avant et après
        return strlen($str) >= 2 && strlen($str) <= 100 && !empty($str);
    }

    private function validateCustomer(string $customerName): void {
        if (in_array($customerName, self::$BLACKLISTED_CUSTOMERS)) {
            throw new Exception('Vous êtes blacklisté');
        }
        if (!$this->isValidString($customerName)) {
            throw new Exception('Le nom du client doit contenir entre 2 et 100 caractères.');
        }
    }

    private function validateProductCount(array $products): void {
        if (count($products) > self::$MAX_PRODUCTS) {
            throw new Exception("Vous ne pouvez pas commander plus de " . self::$MAX_PRODUCTS . " produits");
        }
    }

    private function updateTotalPrice(): void {
        $this->totalPrice = count($this->products) * self::$PRODUCT_PRICE;
        if ($this->shippingMethod === self::$EXPRESS_METHOD) {
            $this->totalPrice += self::$EXPRESS_COST;
        }
    }

    public function addProduct(string $product): void {
        if ($this->status !== self::STATUS_CART) {
            $this->addMessage("Ajout impossible : la commande n'est plus modifiable.", self::MESSAGE_ERROR);
            return;
        }
        if (in_array($product, $this->products)) {
            $this->addMessage("Le produit '$product' est déjà dans la commande.", self::MESSAGE_WARNING);
            return;
        }
        if (count($this->products) >= self::$MAX_PRODUCTS) {
            $this->addMessage("Vous ne pouvez pas ajouter plus de " . self::$MAX_PRODUCTS . " produits.", self::MESSAGE_ERROR);
            return;
        }

        $this->products[] = $product;
        $this->updateTotalPrice();
        $this->addMessage("Le produit '$product' a été ajouté à la commande.", self::MESSAGE_SUCCESS);
    }

    public function removeProduct(string $product): void {
        $key = array_search($product, $this->products);
        if ($key === false) {
            $this->addMessage("Le produit '$product' n'existe pas dans la commande.", self::MESSAGE_WARNING);
            return;
        }
        unset($this->products[$key]);
        $this->products = array_values($this->products);
        $this->updateTotalPrice();
        $this->addMessage("Le produit '$product' a été supprimé de la commande.", self::MESSAGE_SUCCESS);
    }

    public function setShippingCountry(string $country): void {
        if (!in_array($country, self::$AUTHORIZED_COUNTRIES)) {
            throw new Exception('Livraison non autorisée dans ce pays.');
        }
        $this->shippingCountry = $country;
        $this->addMessage("Pays de livraison défini : {$country}.", self::MESSAGE_INFO);
    }

    public function setShippingAddress(string $address): void {
        $this->checkStatus(self::STATUS_CART, 'Définir l\'adresse');
        if (!$this->isValidString($address)) {
            throw new Exception('L\'adresse de livraison doit contenir entre 2 et 100 caractères.');
        }
        $this->shippingAddress = $address;
        $this->status = self::STATUS_SHIPPING_ADDRESS_SET;
        $this->addMessage("Adresse de livraison définie : {$address}.", self::MESSAGE_INFO);
    }

    public function setShippingMethod(string $method): void {
        $this->checkStatus(self::STATUS_SHIPPING_ADDRESS_SET, 'Définir la méthode de livraison');
        if (!in_array($method, self::$SHIPPING_METHODS)) {
            throw new Exception('Méthode de livraison non valide.');
        }
        $this->shippingMethod = $method;
        $this->status = self::STATUS_SHIPPING_METHOD_SET;
        $this->updateTotalPrice();
        $this->addMessage("Méthode de livraison sélectionnée : {$method}.", self::MESSAGE_INFO);
    }

    public function pay(): void {
        $this->checkStatus(self::STATUS_SHIPPING_METHOD_SET, 'Paiement');
        $this->status = self::STATUS_PAID;
        $this->addMessage("La commande a été payée avec succès. Montant total : {$this->totalPrice}€", self::MESSAGE_SUCCESS);
    }

    private function checkStatus(string $expectedStatus, string $action): void {
        if ($this->status !== $expectedStatus) {
            throw new Exception("$action impossible à ce stade.");
        }
    }

    private function addMessage(string $message, string $type): void {
        $this->messages[] = ['message' => $message, 'type' => $type];
    }

    public function getMessages(): array {
        return $this->messages;
    }

    public function getDetails(): array {
        return [
            'id' => $this->id,
            'customer' => $this->customerName,
            'products' => $this->products,
            'total' => $this->totalPrice,
            'status' => $this->status,
        ];
    }
}