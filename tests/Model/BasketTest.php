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

    private $tableDelivery;
    private $flatDelivery;
    private $freeDelivery;

    private $nextRedHalfPriceOffer;
    private $noOffer;

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

        $this->tableDelivery = [
            90 => 0,
            50 => 2.95,
            0 => 4.95,
        ];

        $this->flatDelivery = [
            0 => 4.95,
        ];

        $this->freeDelivery = [];

        $this->nextRedHalfPriceOffer = [
            'R01'
        ];

        $this->noOffer = [];
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

    public function testFlatDelivery()
    {
        $basket = new Basket($this->catalog, $this->flatDelivery);
        $this->assertEquals(0, $basket->countProducts());
        $this->assertEquals(4.95, $basket->total());
    }

    public function testTableDeliveryUnder50()
    {
        $basket = new Basket($this->catalog, $this->tableDelivery);
        $basket->add('G01');
        $this->assertEquals(1, $basket->countProducts());
        $this->assertEquals(24.95 + 4.95, $basket->total());

        $basket->add('B01');
        $this->assertEquals(2, $basket->countProducts());
        $this->assertEquals(24.95 + 7.95 + 4.95, $basket->total());
    }

    public function testTableDeliveryBetween50And90()
    {
        $basket = new Basket($this->catalog, $this->tableDelivery);
        $basket->add('R01');
        $basket->add('R01');
        $this->assertEquals(2, $basket->countProducts());
        $this->assertEquals(32.95 + 32.95 + 2.95, $basket->total());

        $basket->add('B01');
        $this->assertEquals(3, $basket->countProducts());
        $this->assertEquals(32.95 + 32.95 + 7.95 + 2.95, $basket->total());
    }

    public function testTableDeliveryOver90()
    {
        $basket = new Basket($this->catalog, $this->tableDelivery);
        $basket->add('R01');
        $basket->add('R01');
        $basket->add('R01');
        $this->assertEquals(3, $basket->countProducts());
        $this->assertEquals(32.95 + 32.95 + 32.95, $basket->total());

        $basket->add('B01');
        $this->assertEquals(4, $basket->countProducts());
        $this->assertEquals(32.95 + 32.95 + 32.95 + 7.95, $basket->total());
    }

    public function testFreeDeliveryNoOffer()
    {
        $basket = new Basket($this->catalog, $this->freeDelivery);
        $basket->add('R01');
        $this->assertEquals(1, $basket->countProducts());
        $this->assertEquals(32.95, $basket->total());

        $basket = new Basket($this->catalog, $this->freeDelivery, $this->noOffer);
        $basket->add('R01');
        $this->assertEquals(1, $basket->countProducts());
        $this->assertEquals(32.95, $basket->total());
    }

    public function testFreeDeliveryNextRedHalfPriceOffer()
    {
        $basket = new Basket($this->catalog, $this->freeDelivery, $this->nextRedHalfPriceOffer);
        $basket->add('R01');
        $this->assertEquals(1, $basket->countProducts());
        $this->assertEquals(32.95, $basket->total());

        $basket->add('R01');
        $this->assertEquals(2, $basket->countProducts());
        $this->assertEquals(49.42, $basket->total());
    }

    public function testTableDeliveryNextRedHalfPriceOffer()
    {
        $basket = new Basket($this->catalog, $this->tableDelivery, $this->nextRedHalfPriceOffer);
        $basket->add('B01');
        $basket->add('G01');
        $this->assertEquals(2, $basket->countProducts());
        $this->assertEquals(37.85, $basket->total());

        $basket = new Basket($this->catalog, $this->tableDelivery, $this->nextRedHalfPriceOffer);
        $basket->add('R01');
        $basket->add('G01');
        $this->assertEquals(2, $basket->countProducts());
        $this->assertEquals(60.85, $basket->total());

        $basket = new Basket($this->catalog, $this->tableDelivery, $this->nextRedHalfPriceOffer);
        $basket->add('R01');
        $basket->add('R01');
        $this->assertEquals(2, $basket->countProducts());
        $this->assertEquals(54.37, $basket->total());

        $basket = new Basket($this->catalog, $this->tableDelivery, $this->nextRedHalfPriceOffer);
        $basket->add('B01');
        $basket->add('B01');
        $basket->add('R01');
        $basket->add('R01');
        $basket->add('R01');
        $this->assertEquals(5, $basket->countProducts());
        $this->assertEquals(98.27, $basket->total());
    }
}
