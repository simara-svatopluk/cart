<?php

namespace Simara\Cart\Domain;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public function testAdd()
    {
        $item = new Item('x', new Price(5), 2);
        $newItem = $item->add(5);

        $expected = new Item('x', new Price(5), 7);

        Assert::assertEquals($expected, $newItem);
    }

    public function testChangeAmount()
    {
        $item = new Item('x', new Price(5), 2);
        $newItem = $item->withAmount(1);

        $expected = new Item('x', new Price(5), 1);

        Assert::assertEquals($expected, $newItem);
    }

    public function testInitialAmountCannotBeNegative()
    {
        $this->expectException(AmountMustBePositive::class);
        new Item('x', new Price(5), -1);
    }

    public function testInitialAmountCannotBeZero()
    {
        $this->expectException(AmountMustBePositive::class);
        new Item('x', new Price(5), 0);
    }

    public function testAddNegativeThrowsException()
    {
        $this->expectException(AmountMustBePositive::class);
        $item = new Item('x', new Price(5), 1);
        $item->add(-1);
    }

    public function testAddZeroThrowsException()
    {
        $this->expectException(AmountMustBePositive::class);
        $item = new Item('x', new Price(5), 1);
        $item->add(0);
    }

    public function testChangeToNegativeThrowsException()
    {
        $this->expectException(AmountMustBePositive::class);
        $item = new Item('x', new Price(5), 1);
        $item->withAmount(-1);
    }

    public function testChangeToZeroThrowsException()
    {
        $this->expectException(AmountMustBePositive::class);
        $item = new Item('x', new Price(5), 1);
        $item->withAmount(0);
    }
}