<?php

namespace Simara\Cart\Infrastructure;

use Generator;
use PHPUnit\Framework\TestCase;
use Simara\Cart\Domain\Price;
use Simara\Cart\Domain\Prices\PriceNotFoundException;

final class CsvPricesTest extends TestCase
{
    /**
     * @dataProvider successTestCases
     */
    public function testSuccess(string $productId, Price $expected): void
    {
        $prices = new CsvPrices(__DIR__ . '/fixtures/prices.csv');
        $actual = $prices->unitPrice($productId);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return Generator<array<string|Price>>
     */
    public function successTestCases(): Generator
    {
        yield ['p12345', new Price('50.0')];
        yield ['p54321', new Price('100.10')];
    }

    public function testProductHasNoPriceThrowsException(): void
    {
        $this->expectException(PriceNotFoundException::class);

        $prices = new CsvPrices(__DIR__ . '/fixtures/prices.csv');
        $prices->unitPrice('not-product-id');
    }
}
