<?php

namespace App\Tests;

use App\Model\Basket;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class BasketTest extends TestCase
{
    public function testConstructor()
    {
        $basket = new Basket();
        $this->assertInstanceOf(Basket::class, $basket);
    }
}
