<?php

namespace Simara\Cart\Domain;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class PriceTest extends TestCase
{

    public function testAdd()
    {
        $a = new Price("10.0");
        $b = new Price("0.5");
        $result = $a->add($b);

        $expected = new Price("10.5");
        Assert::assertEquals($expected, $result);
    }

    public function testMultiply()
    {
        $a = new Price("10.3");
        $result = $a->multiply(2);

        $expected = new Price("20.6");
        Assert::assertEquals($expected, $result);
    }

    public function testSum()
    {
        $prices = [
            new Price("9.0"),
            new Price("0.7"),
            new Price("0.3"),
        ];

        $sum = Price::sum($prices);
        $expected = new Price("10.0");
        Assert::assertEquals($expected, $sum);
    }

    /**
     * @dataProvider getterTestCases
     */
    public function testGetter(string $converted, string $expected)
    {
        $price = new Price($converted);
        $this->assertSame($expected, $price->getWithVat());
    }

    public function getterTestCases(): array
    {
        return [
            ["0.005", "0"],
            ["0.05" , "0.05"],
            ["0.5"  , "0.5"],
            ["0"    , "0"],
            ["5"    , "5"],
            ["5.555", "5.55"],
        ];
    }
}
