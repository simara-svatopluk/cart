<?php

namespace Simara\Cart\Domain\Detail;

use Simara\Cart\Domain\Price;

final class CartDetail
{
    /**
     * @param $items array<int, ItemDetail>
     */
    public function __construct(
        private array $items,
        private Price $totalPrice,
    ) {
    }

    /**
     * @return array<int, ItemDetail>
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
