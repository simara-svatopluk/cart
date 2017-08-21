<?php

namespace Simara\Cart\Domain;

class CartDetail
{

    /**
     * @var DetailItem[]
     */
    private $items;

    /**
     * @var Price
     */
    private $totalPrice;

    public function __construct(array $items, Price $totalPrice)
    {
        $this->items = $items;
        $this->totalPrice = $totalPrice;
    }

    /**
     * @return DetailItem[]
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
