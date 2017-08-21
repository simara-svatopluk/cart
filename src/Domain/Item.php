<?php

namespace Simara\Cart\Domain;

class Item
{

    /**
     * @var string
     */
    private $productId;

    /**
     * @var int
     */
    private $amount;

    /**
     * @var Price
     */
    private $unitPrice;

    public function __construct(string $productId, Price $unitPrice, int $amount)
    {
        $this->productId = $productId;
        $this->amount = $amount;
        $this->unitPrice = $unitPrice;
    }

    public function toDetail(): DetailItem
    {
        return new DetailItem($this->productId, $this->unitPrice, $this->amount);
    }

    public function add(int $amount)
    {
    }
}
