<?php

declare(strict_types=1);

namespace Simara\Cart\Domain\Cart;

use Simara\Cart\Domain\Price;
use Simara\Cart\Domain\Prices\Prices;

final class StaticPrices implements Prices
{
    /**
     * @var array<string, Price>
     */
    private array $prices;

    /**
     * @param array<string, Price> $prices
     */
    public function __construct($prices)
    {
        $this->prices = $prices;
    }

    public function unitPrice(string $productId): Price
    {
        return $this->prices[$productId];
    }
}
