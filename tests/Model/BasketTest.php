<?php

namespace App\Tests;

use App\Model\Basket;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class BasketTest extends TestCase
{
    private $basket;

    private $redWidget;
    private $greenWidget;
    private $blueWidget;

    protected function setUp()
    {
        parent::setUp();

        $this->redWidget = (new Product())
            ->setName('Red Widget')
            ->setCode('R01')
            ->setPrice(32.95)
        ;

        $this->greenWidget = (new Product())
            ->setName('Green Widget')
            ->setCode('G01')
            ->setPrice(24.95)
        ;

        $this->blueWidget = (new Product())
            ->setName('Blue Widget')
            ->setCode('B01')
            ->setPrice(7.95)
        ;

        $products = [
            $this->redWidget,
            $this->greenWidget,
            $this->blueWidget,
        ];

        $this->basket = new Basket($products);
    }

    public function testConstructor()
    {
        $this->assertInstanceOf(Basket::class, $this->basket);
    }

    public function testAdd()
    {
        $this->assertEquals(0, $this->basket->countProducts());

        $this->basket->add($this->redWidget->getCode());
        $this->assertEquals(1, $this->basket->countProducts());
    }

    public function testAddTwoDifferentProducts()
    {
        $this->basket->add($this->greenWidget->getCode());
        $this->assertEquals(1, $this->basket->countProducts());

        $this->basket->add($this->blueWidget->getCode());
        $this->assertEquals(2, $this->basket->countProducts());
    }

    public function testAddSameProductTwice()
    {
        $this->basket->add($this->redWidget->getCode());
        $this->assertEquals(1, $this->basket->countProducts());

        $this->basket->add($this->redWidget->getCode());
        $this->assertEquals(2, $this->basket->countProducts());
    }
}
