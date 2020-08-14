<?php

namespace Simara\Cart\Domain\Cart;

use Simara\Cart\Domain\Price;

final class ItemDetail
{
    private string $productId;

    private Price $price;

    private int $amount;

    public function __construct(string $productId, Price $price, int $amount)
    {
        $this->productId = $productId;
        $this->amount = $amount;
        $this->price = $price;
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
