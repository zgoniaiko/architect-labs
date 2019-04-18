<?php

namespace App\Tests;

use App\Model\Basket;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class BasketTest extends TestCase
{
    private $redWidget;
    private $greenWidget;
    private $blueWidget;

    private $catalog;

    private $delivery;
    private $flatDelivery;
    private $freeDelivery;

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

        $this->catalog = [
            $this->redWidget,
            $this->greenWidget,
            $this->blueWidget,
        ];

        $this->delivery = [
            90 => 0,
            50 => 2.95,
            0 => 4.95,
        ];

        $this->flatDelivery = [
            0 => 4.95,
        ];

        $this->freeDelivery = [];
    }

    public function testConstructor()
    {
        $basket = new Basket($this->catalog);
        $this->assertInstanceOf(Basket::class, $basket);
    }

    public function testAdd()
    {
        $basket = new Basket($this->catalog);
        $this->assertEquals(0, $basket->countProducts());
        $this->assertEquals(0, $basket->total());

        $basket->add('R01');
        $this->assertEquals(1, $basket->countProducts());
        $this->assertEquals(32.95, $basket->total());
    }

    public function testAddTwoDifferentProducts()
    {
        $basket = new Basket($this->catalog);
        $basket->add('G01');
        $this->assertEquals(1, $basket->countProducts());
        $this->assertEquals(24.95, $basket->total());

        $basket->add('B01');
        $this->assertEquals(2, $basket->countProducts());
        $this->assertEquals(24.95 + 7.95, $basket->total());
    }

    public function testAddSameProductTwice()
    {
        $basket = new Basket($this->catalog);
        $basket->add('R01');
        $this->assertEquals(1, $basket->countProducts());
        $this->assertEquals(32.95, $basket->total());

        $basket->add('R01');
        $this->assertEquals(2, $basket->countProducts());
        $this->assertEquals(32.95 + 32.95, $basket->total());
    }

    public function testAddNonExistsProduct()
    {
        $basket = new Basket($this->catalog);
        $this->expectException('\Exception');
        $basket->add('non-exists');
    }

    public function testFreeDelivery()
    {
        $basket = new Basket($this->catalog);
        $this->assertEquals(0, $basket->countProducts());
        $this->assertEquals(0, $basket->total());

        $basket = new Basket($this->catalog, $this->freeDelivery);
        $this->assertEquals(0, $basket->countProducts());
        $this->assertEquals(0, $basket->total());
    }

}
