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
        $this->checkAmount($amount);
        $this->productId = $productId;
        $this->amount = $amount;
        $this->unitPrice = $unitPrice;
    }

    public function toDetail(): DetailItem
    {
        return new DetailItem($this->productId, $this->unitPrice, $this->amount);
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function add(int $amount)
    {
        $this->checkAmount($amount);
        $this->amount = $this->amount + $amount;
    }

    private function checkAmount(int $amount)
    {
        if ($amount <= 0) {
            throw new AmountMustBePositiveException();
        }
    }
}
