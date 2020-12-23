<?php

namespace Simara\Cart\Domain\Detail;

use Simara\Cart\Domain\Price;

final class ItemDetail
{
    public function __construct(
        private string $productId,
        private Price $unitPrice,
        private int $amount
    ) {
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getUnitPrice(): Price
    {
        return $this->unitPrice;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
