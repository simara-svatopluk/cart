<?php

namespace Simara\Cart\Domain;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{

    public function testCalculateEmptyCart()
    {
        $cart = new Cart('1');

        $expected = new CartDetail([], new Price("0.0"));

        Assert::assertEquals($expected, $cart->calculate($this->createConstantPrices("0.0")));
    }

    public function testAddSingleProductToEmpty()
    {
        $cart = new Cart('1');
        $cart->add('a');

        $expectedItem = new ItemDetail('a', new Price("10.0"), 1);
        $expected = new CartDetail([$expectedItem], new Price("10.0"));

        Assert::assertEquals($expected, $cart->calculate($this->createConstantPrices("10.0")));
    }

    public function testAddTwoDifferentProducts()
    {
        $cart = new Cart('1');
        $cart->add('a');
        $cart->add('b', 2);

        $expectedItems = [
            new ItemDetail('a', new Price("10.0"), 1),
            new ItemDetail('b', new Price("20.0"), 2),
        ];
        $expected = new CartDetail($expectedItems, new Price("50.0"));

        $prices = [
        	'a' => new Price("10.0"),
			'b' => new Price("20.0"),
		];
        Assert::assertEquals($expected, $cart->calculate($this->createStaticPrices($prices)));
    }

    public function testAddSameProductIncrementAmountOnly()
    {
        $cart = new Cart('1');
        $cart->add('a');
        $cart->add('a');

        $expectedItem = new ItemDetail('a', new Price("10.0"), 2);
        $expected = new CartDetail([$expectedItem], new Price("20.0"));

        Assert::assertEquals($expected, $cart->calculate($this->createConstantPrices("10.0")));
    }

    public function testRemoveNotExistingProductFromEmptyCart()
    {
        $this->expectException(ProductNotInCartException::class);

        $cart = new Cart('1');
        $cart->remove('x');
    }

    public function testRemoveNotExistingProduct()
    {
        $this->expectException(ProductNotInCartException::class);

        $cart = new Cart('1');
        $cart->add('a');
        $cart->remove('x');
    }

    public function testRemoveProduct()
    {
        $cart = new Cart('1');
        $cart->add('a');
        $cart->add('b', 2);

        $cart->remove('a');

        $expectedItems = [
            new ItemDetail('b', new Price("20.0"), 2),
        ];
        $expected = new CartDetail($expectedItems, new Price("40.0"));

        Assert::assertEquals($expected, $cart->calculate($this->createConstantPrices("20.0")));
    }

    public function testChangeAmountOfNotExisting()
    {
        $this->expectException(ProductNotInCartException::class);

        $cart = new Cart('1');
        $cart->add('a');

        $cart->changeAmount('x', 5);
    }

    public function testChangeAmount()
    {
        $cart = new Cart('1');
        $cart->add('a');
        $cart->changeAmount('a', 5);

        $expectedItem = new ItemDetail('a', new Price("10.0"), 5);
        $expected = new CartDetail([$expectedItem], new Price("50.0"));

        Assert::assertEquals($expected, $cart->calculate($this->createConstantPrices("10.0")));
    }

    public function testGetGetId()
    {
        $cart = new Cart('1');
        Assert::assertSame('1', $cart->getId());
    }

	private function createConstantPrices(string $price): Prices
	{
		return new ConstantPrices(new Price($price));
	}

	private function createStaticPrices(array $prices): Prices {
    	return new StaticPrices($prices);
	}
}
