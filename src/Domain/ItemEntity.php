<?php

namespace Simara\Cart\Domain;

use JetBrains\PhpStorm\Pure;

class ItemEntity
{
    private Item $item;

    /**
     * @throws AmountMustBePositive
     */
    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    #[Pure]
    public function toItem(): Item
    {
        return $this->item;
    }

    #[Pure]
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
