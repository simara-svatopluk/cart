<?php

declare(strict_types=1);

namespace Simara\Cart\Domain\Cart;

use Simara\Cart\Domain\Price;
use Simara\Cart\Domain\Prices\Prices;

final class StaticPrices implements Prices
{
    /**
     * @param array<string, Price> $prices
     */
    public function __construct(private array $prices)
    {
    }

    public function unitPrice(string $productId): Price
    {
        return $this->prices[$productId];
    }
}
