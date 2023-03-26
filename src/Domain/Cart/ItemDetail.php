<?php

namespace Simara\Cart\Domain\Cart;

use Simara\Cart\Domain\Price;

final class ItemDetail
{
    public function __construct(
        private string $productId,
        private Price $price,
        private int $amount
    ) {
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }
}
