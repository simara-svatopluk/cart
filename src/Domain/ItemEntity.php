<?php

namespace Simara\Cart\Domain;

class ItemEntity
{
    private Item $item;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    public function toItem(): Item
    {
        return $this->item;
    }

    public function getProductId(): string
    {
        return $this->item->getProductId();
    }

    /**
     * @throws AmountMustBePositive
     */
    public function add(int $amount): void
    {
        $this->item = $this->item->add($amount);
    }

    /**
     * @throws AmountMustBePositive
     */
    public function changeAmount(int $amount): void
    {
        $this->item = $this->item->withAmount($amount);
    }
}
