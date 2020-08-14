<?php

declare(strict_types=1);

namespace Simara\Cart\Domain\Prices;

use Simara\Cart\Domain\Price;

interface Prices
{
    /**
     * @throws PriceNotFoundException
     */
    public function unitPrice(string $productId): Price;
}
