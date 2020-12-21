<?php

namespace Simara\Cart\Domain\Detail;

use Simara\Cart\Domain\Item;
use Simara\Cart\Domain\Price;

class CartDetail
{
    /**
     * @param $items array<int, Item>
     */
    public function __construct(
        private array $items,
        private Price $totalPrice,
    ) {
    }

    /**
     * @return array<int, Item>
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
