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
}
