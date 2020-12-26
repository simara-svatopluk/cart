<?php

namespace Simara\Cart\Domain\Cart;

use Simara\Cart\Domain\Price;

final class CartDetail
{
    /**
     * @param ItemDetail[] $items
     */
    public function __construct(
        private array $items,
        private Price $totalPrice
    ) {
    }

    /**
     * @return ItemDetail[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotalPrice(): Price
    {
        return $this->totalPrice;
    }
}
