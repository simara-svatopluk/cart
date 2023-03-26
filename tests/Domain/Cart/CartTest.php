<?php

namespace Simara\Cart\Domain\Cart;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Simara\Cart\Domain\Price;
use Simara\Cart\Domain\Prices\Prices;

final class CartTest extends TestCase
{
    public function testCalculateEmptyCart(): void
    {
        $cart = new Cart('1');

        $expected = new CartDetail([], new Price("0.0"));

        Assert::assertEquals($expected, $cart->calculate($this->createConstantPrices("0.0")));
    }

    public function testAddSingleProductToEmpty(): void
    {
        $cart = new Cart('1');
        $cart->add('a');

        $expectedItem = new ItemDetail('a', new Price("10.0"), 1);
        $expected = new CartDetail([$expectedItem], new Price("10.0"));

        Assert::assertEquals($expected, $cart->calculate($this->createConstantPrices("10.0")));
    }

    public function testAddTwoDifferentProducts(): void
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

    public function testAddSameProductIncrementAmountOnly(): void
    {
        $cart = new Cart('1');
        $cart->add('a');
        $cart->add('a');

        $expectedItem = new ItemDetail('a', new Price("10.0"), 2);
        $expected = new CartDetail([$expectedItem], new Price("20.0"));

        Assert::assertEquals($expected, $cart->calculate($this->createConstantPrices("10.0")));
    }

    public function testRemoveNotExistingProductFromEmptyCart(): void
    {
        $this->expectException(ProductNotInCartException::class);

        $cart = new Cart('1');
        $cart->remove('x');
    }

    public function testRemoveNotExistingProduct(): void
    {
        $this->expectException(ProductNotInCartException::class);

        $cart = new Cart('1');
        $cart->add('a');
        $cart->remove('x');
    }

    public function testRemoveProduct(): void
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

    public function testChangeAmountOfNotExisting(): void
    {
        $this->expectException(ProductNotInCartException::class);

        $cart = new Cart('1');
        $cart->add('a');

        $cart->changeAmount('x', 5);
    }

    public function testChangeAmount(): void
    {
        $cart = new Cart('1');
        $cart->add('a');
        $cart->changeAmount('a', 5);

        $expectedItem = new ItemDetail('a', new Price("10.0"), 5);
        $expected = new CartDetail([$expectedItem], new Price("50.0"));

        Assert::assertEquals($expected, $cart->calculate($this->createConstantPrices("10.0")));
    }

    public function testGetGetId(): void
    {
        $cart = new Cart('1');
        Assert::assertSame('1', $cart->getId());
    }

    private function createConstantPrices(string $price): Prices
    {
        return new ConstantPrices(new Price($price));
    }

    /**
     * @param array<string, Price> $prices
     */
    private function createStaticPrices(array $prices): Prices
    {
        return new StaticPrices($prices);
    }
}
