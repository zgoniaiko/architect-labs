<?php

namespace App\Model;

class Basket
{
    private $catalog;

    private $products = [];

    public function __construct(array $catalog)
    {
        foreach ($catalog as $item) {
            $this->catalog[$item->getCode()] = $item;
        }
    }

    public function add($code)
    {
        $this->products[] = $code;
    }

    public function countProducts()
    {
        return count($this->products);
    }

    public function total()
    {
        $total = 0;
        foreach ($this->products as $item) {
            $total += $this->catalog[$item]->getPrice();
        }

        return $total;
    }
}
