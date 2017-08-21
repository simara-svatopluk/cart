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
}
