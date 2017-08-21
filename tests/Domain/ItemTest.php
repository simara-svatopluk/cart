<?php

namespace Simara\Cart\Domain;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{

    public function testToDetail()
    {
        $item = new Item('x', new Price(5.0), 2);

        $expected = new DetailItem('x', new Price(5.0), 2);

        Assert::assertEquals($expected, $item->toDetail());
    }

    public function testAdd()
    {
        $item = new Item('x', new Price(5.0), 2);
        $item->add(5);

        $expected = new DetailItem('x', new Price(5.0), 7);

        Assert::assertEquals($expected, $item->toDetail());
    }

    public function testInitialAmountCannotBeNegative()
    {
        $this->expectException(AmountMustBePositiveException::class);
        new Item('x', new Price(5.0), -1);
    }

    public function testInitialAmountCannotBeZero()
    {
        $this->expectException(AmountMustBePositiveException::class);
        new Item('x', new Price(5.0), 0);
    }

    public function testAddNegativeThrowsException()
    {
        $this->expectException(AmountMustBePositiveException::class);
        $item = new Item('x', new Price(5.0), 1);
        $item->add(-1);
    }

    public function testAddZeroThrowsException()
    {
        $this->expectException(AmountMustBePositiveException::class);
        $item = new Item('x', new Price(5.0), 1);
        $item->add(0);
    }
}
