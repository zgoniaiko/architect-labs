<?php

namespace App\Model;

use Exception;

class Basket
{
    private $catalog;
    private $delivery;

    private $products = [];

    public function __construct(array $catalog, array $delivery = [])
    {
        foreach ($catalog as $item) {
            $this->catalog[$item->getCode()] = $item;
        }

        $this->delivery = $delivery;
    }

    public function add($code)
    {
        if (!isset($this->catalog[$code])) {
            throw new Exception(sprintf("Attempt to add non-exists product with code '%s'", $code));
        }

        $this->products[] = $code;
    }

    public function countProducts()
    {
        return count($this->products);
    }

    public function total()
    {
        $total = $this->getProductsCost();
        $delivery = $this->getDeliveryCost();

        return $total + $delivery;
    }

    private function getDeliveryCost()
    {
        $total = $this->getProductsCost();

        foreach ($this->delivery as $amount => $cost) {
            if ($total >= $amount) {
                return $cost;
            }
        }
    }

    private function getProductsCost()
    {
        $total = 0;
        foreach ($this->products as $item) {
            $total += $this->catalog[$item]->getPrice();
        }

        return $total;
    }
}
