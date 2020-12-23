<?php

namespace Simara\Cart\Domain;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Simara\Cart\Domain\Detail\CartDetail;
use Simara\Cart\Domain\Detail\ItemDetail;

class CartTest extends TestCase
{

    public function testCalculateEmptyCartResultsInEmptyDetail(): void
    {
        $cart = new Cart();

        $expected = new CartDetail([], new Price(0));

        Assert::assertEquals($expected, $cart->calculate());
    }

    public function testAddSingleProductToEmptyResultsInDetailWithSingleItem(): void
    {
        $cart = new Cart();
        $cart->add('a', new Price(10));

        $expectedItem = new ItemDetail('a', new Price(10), 1);
        $expected = new CartDetail([$expectedItem], new Price(10));

        Assert::assertEquals($expected, $cart->calculate());
    }

    public function testAddTwoDifferentProductsResultsInTwoItems(): void
    {
        $cart = new Cart();
        $cart->add('a', new Price(10));
        $cart->add('b', new Price(20), 2);

        $expectedItems = [
            new ItemDetail('a', new Price(10), 1),
            new ItemDetail('b', new Price(20), 2),
        ];
        $expected = new CartDetail($expectedItems, new Price(50));

        Assert::assertEquals($expected, $cart->calculate());
    }

    public function testAddSameProductResultsInOneItemWithHigherAmount(): void
    {
        $cart = new Cart();
        $cart->add('a', new Price(10));
        $cart->add('a', new Price(0));

        $expectedItem = new ItemDetail('a', new Price(10), 2);
        $expected = new CartDetail([$expectedItem], new Price(20));

        Assert::assertEquals($expected, $cart->calculate());
    }

    public function testRemoveNotExistingProductFromEmptyCartResultsInException(): void
    {
        $this->expectException(ProductNotInCart::class);

        $cart = new Cart();
        $cart->remove('x');
    }

    public function testRemoveNotExistingProductResultsInException(): void
    {
        $this->expectException(ProductNotInCart::class);

        $cart = new Cart();
        $cart->add('a', new Price(10));
        $cart->remove('x');
    }

    public function testRemoveProductResultsInRemovedItem(): void
    {
        $cart = new Cart();
        $cart->add('a', new Price(10));
        $cart->add('b', new Price(20), 2);

        $cart->remove('a');

        $expectedItems = [
            new ItemDetail('b', new Price(20), 2),
        ];
        $expected = new CartDetail($expectedItems, new Price(40));

        Assert::assertEquals($expected, $cart->calculate());
    }

    public function testChangeAmountOfNotExistingResultsInException(): void
    {
        $this->expectException(ProductNotInCart::class);

        $cart = new Cart();
        $cart->add('a', new Price(10));

        $cart->changeAmount('x', 5);
    }

    public function testChangeAmountResultsInTheNewAmount(): void
    {
        $cart = new Cart();
        $cart->add('a', new Price(10));
        $cart->changeAmount('a', 5);

        $expectedItem = new ItemDetail('a', new Price(10), 5);
        $expected = new CartDetail([$expectedItem], new Price(50));

        Assert::assertEquals($expected, $cart->calculate());
    }
}
