<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class VendorMachine
{
    private bool $isOn;
    private int $snacksQty;
    private int $money;

    public function __construct()
    {
        $this->isOn = false;
        $this->snacksQty = 50;
        $this->money = 0;
    }

    public function buySnack(): void
    {
        $this->isOn = true;

        if ($this->snacksQty === 0) {
            $this->reset();
        }

        if ($this->snacksQty > 0) {
            $this->snacksQty -= 1;
            $this->money += 2;
        }
    }

    public function reset(): void
    {
        $this->isOn = false;
        $this->snacksQty = 50;
        $this->money = 0;
        $this->isOn = true;
    }

    public function shootWithFoot(): string
    {
        $this->isOn = false;

        $moneyDropped = $this->dropMoney();
        $snacksDropped = $this->dropSnacks();

        return "Vous avez récupéré $moneyDropped € et $snacksDropped snacks.";
    }

    private function dropMoney(): int
    {
        $moneyToDrop = 20;
        if ($this->money < 20) {
            $moneyToDrop = $this->money;
        }
        $this->money -= $moneyToDrop;

        return $moneyToDrop;
    }

    private function dropSnacks(): int
    {
        $snackQtyToDrop = 5;

        if ($this->snacksQty < 5) {
            $snackQtyToDrop = $this->snacksQty;
        }

        $this->snacksQty -= $snackQtyToDrop;

        return $snackQtyToDrop;
    }
}

$vendorMachine = new VendorMachine();

echo $vendorMachine->shootWithFoot();
