<?php

namespace Simara\Cart\Domain\Cart;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Simara\Cart\Domain\Cart\AmountMustBePositiveException;
use Simara\Cart\Domain\Cart\ConstantPrices;
use Simara\Cart\Domain\Cart\Item;
use Simara\Cart\Domain\Cart\ItemDetail;
use Simara\Cart\Domain\Price;
use Simara\Cart\Domain\Prices\Prices;

final class ItemTest extends TestCase
{

    public function testToDetail(): void
    {
        $item = new Item('x', 2);

        $expected = new ItemDetail('x', new Price("5.0"), 2);

        Assert::assertEquals($expected, $item->toDetail($this->createConstantPrices("5.0")));
    }

    public function testAdd(): void
    {
        $item = new Item('x', 2);
        $item->add(5);

        $expected = new ItemDetail('x', new Price("5.0"), 7);

        Assert::assertEquals($expected, $item->toDetail($this->createConstantPrices("5.0")));
    }

    public function testChangeAmount(): void
    {
        $item = new Item('x', 2);
        $item->changeAmount(1);

        $expected = new ItemDetail('x', new Price("5.0"), 1);

        Assert::assertEquals($expected, $item->toDetail($this->createConstantPrices("5.0")));
    }

    public function testInitialAmountCannotBeNegative(): void
    {
        $this->expectException(AmountMustBePositiveException::class);
        new Item('x', -1);
    }

    public function testInitialAmountCannotBeZero(): void
    {
        $this->expectException(AmountMustBePositiveException::class);
        new Item('x', 0);
    }

    public function testAddNegativeThrowsException(): void
    {
        $this->expectException(AmountMustBePositiveException::class);
        $item = new Item('x', 1);
        $item->add(-1);
    }

    public function testAddZeroThrowsException(): void
    {
        $this->expectException(AmountMustBePositiveException::class);
        $item = new Item('x', 1);
        $item->add(0);
    }

    public function testChangeToNegativeThrowsException(): void
    {
        $this->expectException(AmountMustBePositiveException::class);
        $item = new Item('x', 1);
        $item->changeAmount(-1);
    }

    public function testChangeToZeroThrowsException(): void
    {
        $this->expectException(AmountMustBePositiveException::class);
        $item = new Item('x', 1);
        $item->changeAmount(0);
    }

    public function testCalculateTotalPrice(): void
    {
        $item = new Item('x', 3);
        $price = $item->calculatePrice($this->createConstantPrices("5.0"));

        $expected = new Price("15.0");
        Assert::assertEquals($expected, $price);
    }

    private function createConstantPrices(string $price): Prices
    {
        return new ConstantPrices(new Price($price));
    }
}
