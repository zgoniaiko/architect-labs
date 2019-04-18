<?php

namespace App\Tests;

use App\Model\Basket;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class BasketTest extends TestCase
{
    private $basket;

    protected function setUp()
    {
        parent::setUp();

        $this->basket = new Basket();
    }

    public function testConstructor()
    {
        $this->assertInstanceOf(Basket::class, $this->basket);
    }

    public function testAdd()
    {
        $product = new Product();
        $product
            ->setName('Red Widget')
            ->setCode('R01')
            ->setPrice(32.95)
        ;

        $this->assertEquals(0, $this->basket->countProducts());

        $this->basket->add($product->getCode());
        $this->assertEquals(1, $this->basket->countProducts());

        $this->basket->add($product->getCode());
        $this->assertEquals(2, $this->basket->countProducts());
    }
}
