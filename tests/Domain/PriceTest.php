<?php

namespace Simara\Cart\Domain;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class PriceTest extends TestCase
{
    public function testAdd(): void
    {
        $a = new Price(10);
        $b = new Price(1);
        $result = $a->add($b);

        $expected = new Price(11);
        Assert::assertEquals($expected, $result);
    }

    public function testMultiply(): void
    {
        $a = new Price(10);
        $result = $a->multiply(2);

        $expected = new Price(20);
        Assert::assertEquals($expected, $result);
    }

    public function testSum(): void
    {
        $prices = [
            new Price(9),
            new Price(7),
            new Price(3),
        ];

        $sum = Price::sum($prices);
        $expected = new Price(19);
        Assert::assertEquals($expected, $sum);
    }
}
