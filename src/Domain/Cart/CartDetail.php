<?php

namespace Simara\Cart\Domain\Cart;

use Simara\Cart\Domain\Price;

class CartDetail
{
    /**
     * @var ItemDetail[]
     */
    private array $items;

    private Price $totalPrice;

    /**
     * @param ItemDetail[] $items
     */
    public function __construct(array $items, Price $totalPrice)
    {
        $this->items = $items;
        $this->totalPrice = $totalPrice;
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
