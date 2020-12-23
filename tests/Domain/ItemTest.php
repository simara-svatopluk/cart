<?php

namespace Simara\Cart\Domain;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Simara\Cart\Domain\Detail\ItemDetail;

class ItemTest extends TestCase
{
    public function testAdd(): void
    {
        $item = new Item('x', new Price(5), 2);
        $item->add(5);

        $expected = new ItemDetail('x', new Price(5), 7);

        Assert::assertEquals($expected, $item->toDetail());
    }

    public function testChangeAmount(): void
    {
        $item = new Item('x', new Price(5), 2);
        $item->changeAmount(1);

        $expected = new ItemDetail('x', new Price(5), 1);

        Assert::assertEquals($expected, $item->toDetail());
    }

    public function testInitialAmountCannotBeNegative(): void
    {
        $this->expectException(AmountMustBePositive::class);
        new Item('x', new Price(5), -1);
    }

    public function testInitialAmountCannotBeZero(): void
    {
        $this->expectException(AmountMustBePositive::class);
        new Item('x', new Price(5), 0);
    }

    public function testAddNegativeThrowsException(): void
    {
        $this->expectException(AmountMustBePositive::class);
        $item = new Item('x', new Price(5), 1);
        $item->add(-1);
    }

    public function testAddZeroThrowsException(): void
    {
        $this->expectException(AmountMustBePositive::class);
        $item = new Item('x', new Price(5), 1);
        $item->add(0);
    }

    public function testChangeToNegativeThrowsException(): void
    {
        $this->expectException(AmountMustBePositive::class);
        $item = new Item('x', new Price(5), 1);
        $item->changeAmount(-1);
    }

    public function testChangeToZeroThrowsException(): void
    {
        $this->expectException(AmountMustBePositive::class);
        $item = new Item('x', new Price(5), 1);
        $item->changeAmount(0);
    }
}
