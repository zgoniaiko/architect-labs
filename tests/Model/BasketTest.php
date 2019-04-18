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

    public function testAdd()
    {
        $product = new Product();
        $product
            ->setName('Red Widget')
            ->setCode('R01')
            ->setPrice(32.95)
        ;
        $basket = new Basket();

        $this->assertEquals(0, $basket->countProducts());

        $basket->add($product->getCode());
        $this->assertEquals(1, $basket->countProducts());

        $basket->add($product->getCode());
        $this->assertEquals(2, $basket->countProducts());
    }
}
