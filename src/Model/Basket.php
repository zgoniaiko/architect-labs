<?php

namespace App\Model;

class Basket
{
    private $catalog;

    private $products = [];

    public function __construct(array $catalog)
    {
        $this->catalog = $catalog;
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

    }
}
