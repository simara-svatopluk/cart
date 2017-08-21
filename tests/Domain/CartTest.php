<?php

namespace Simara\Cart\Domain;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{

    public function testCalculateEmptyCart()
    {
        $cart = new Cart;

        $expected = new CartDetail([], new Price(0.0));

        Assert::assertEquals($expected, $cart->calculate());
    }

    public function testAddSingleProductToEmpty()
    {
        $cart = new Cart();
        $cart->add('a', new Price(10.0));

        $expectedItem = new DetailItem('a', new Price(10.0), 1);
        $expected = new CartDetail([$expectedItem], new Price(10.0));

        Assert::assertEquals($expected, $cart->calculate());
    }

    public function testAddTwoDifferentProducts()
    {
        $cart = new Cart();
        $cart->add('a', new Price(10.0));
        $cart->add('b', new Price(20.0), 2);

        $expectedItems = [
            new DetailItem('a', new Price(10.0), 1),
            new DetailItem('b', new Price(20.0), 2),
        ];
        $expected = new CartDetail($expectedItems, new Price(50.0));

        Assert::assertEquals($expected, $cart->calculate());
    }

    public function testAddSameProductIncrementAmountOnly()
    {
        $cart = new Cart();
        $cart->add('a', new Price(10.0));
        $cart->add('a', new Price(0.0));

        $expectedItem = new DetailItem('a', new Price(10.0), 2);
        $expected = new CartDetail([$expectedItem], new Price(20.0));

        Assert::assertEquals($expected, $cart->calculate());
    }

    public function testRemoveNotExistingProductFromEmptyCart()
    {
        $this->expectException(ProductNotInCartException::class);

        $cart = new Cart();
        $cart->remove('x');
    }

    public function testRemoveNotExistingProduct()
    {
        $this->expectException(ProductNotInCartException::class);

        $cart = new Cart();
        $cart->add('a', new Price(10.0));
        $cart->remove('x');
    }

    public function testRemoveProduct()
    {
        $cart = new Cart();
        $cart->add('a', new Price(10.0));
        $cart->add('b', new Price(20.0), 2);

        $cart->remove('a');

        $expectedItems = [
            new DetailItem('b', new Price(20.0), 2),
        ];
        $expected = new CartDetail($expectedItems, new Price(40.0));

        Assert::assertEquals($expected, $cart->calculate());
    }

    public function testChangeAmountOfNotExisting()
    {
        $this->expectException(ProductNotInCartException::class);

        $cart = new Cart();
        $cart->add('a', new Price(10.0));

        $cart->changeAmount('x', 5);
    }

    public function testChangeAmount()
    {
        $cart = new Cart();
        $cart->add('a', new Price(10.0));
        $cart->changeAmount('a', 5);

        $expectedItem = new DetailItem('a', new Price(10.0), 5);
        $expected = new CartDetail([$expectedItem], new Price(50.0));

        Assert::assertEquals($expected, $cart->calculate());
    }
}
