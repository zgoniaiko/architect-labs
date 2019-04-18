<?php

namespace App\Model;

class Basket
{
    private $products = [];

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

    }
}
