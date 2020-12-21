<?php

namespace Simara\Cart\Domain;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Simara\Cart\Domain\Detail\CartDetail;

class CartTest extends TestCase
{

    public function testCalculateEmptyCart(): void
    {
        $cart = new Cart();

        $expected = new CartDetail([], new Price(0));

        Assert::assertEquals($expected, $cart->calculate());
    }

    public function testAddSingleProductToEmpty(): void
    {
        $cart = new Cart();
        $cart->add(new Item('a', new Price(10)));

        $expectedItem = new Item('a', new Price(10), 1);
        $expected = new CartDetail([$expectedItem], new Price(10));

        Assert::assertEquals($expected, $cart->calculate());
    }

    public function testAddTwoDifferentProducts(): void
    {
        $cart = new Cart();
        $cart->add(new Item('a', new Price(10)));
        $cart->add(new Item('b', new Price(20), 2));

        $expectedItems = [
            new Item('a', new Price(10), 1),
            new Item('b', new Price(20), 2),
        ];
        $expected = new CartDetail($expectedItems, new Price(50));

        Assert::assertEquals($expected, $cart->calculate());
    }

    public function testAddSameProductIncrementAmountOnly(): void
    {
        $cart = new Cart();
        $cart->add(new Item('a', new Price(10)));
        $cart->add(new Item('a', new Price(0)));

        $expectedItem = new Item('a', new Price(10), 2);
        $expected = new CartDetail([$expectedItem], new Price(20));

        Assert::assertEquals($expected, $cart->calculate());
    }

    public function testRemoveNotExistingProductFromEmptyCart(): void
    {
        $this->expectException(ProductNotInCart::class);

        $cart = new Cart();
        $cart->remove('x');
    }

    public function testRemoveNotExistingProduct(): void
    {
        $this->expectException(ProductNotInCart::class);

        $cart = new Cart();
        $cart->add(new Item('a', new Price(10)));
        $cart->remove('x');
    }

    public function testRemoveProduct(): void
    {
        $cart = new Cart();
        $cart->add(new Item('a', new Price(10)));
        $cart->add(new Item('b', new Price(20), 2));

        $cart->remove('a');

        $expectedItems = [
            new Item('b', new Price(20), 2),
        ];
        $expected = new CartDetail($expectedItems, new Price(40));

        Assert::assertEquals($expected, $cart->calculate());
    }

    public function testChangeAmountOfNotExisting(): void
    {
        $this->expectException(ProductNotInCart::class);

        $cart = new Cart();
        $cart->add(new Item('a', new Price(10)));

        $cart->changeAmount('x', 5);
    }

    public function testChangeAmount(): void
    {
        $cart = new Cart();
        $cart->add(new Item('a', new Price(10)));
        $cart->changeAmount('a', 5);

        $expectedItem = new Item('a', new Price(10), 5);
        $expected = new CartDetail([$expectedItem], new Price(50));

        Assert::assertEquals($expected, $cart->calculate());
    }
}
