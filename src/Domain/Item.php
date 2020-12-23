<?php

namespace Simara\Cart\Domain;

use Simara\Cart\Domain\Detail\ItemDetail;

final class Item
{
    private string $productId;
    private Price $unitPrice;
    private int $amount;

    /**
     * @throws AmountMustBePositive
     */
    public function __construct(
        string $productId,
        Price $unitPrice,
        int $amount
    ) {
        $this->checkAmount($amount);
        $this->productId = $productId;
        $this->unitPrice = $unitPrice;
        $this->amount = $amount;
    }

    public function toDetail(): ItemDetail
    {
        return new ItemDetail($this->productId, $this->unitPrice, $this->amount);
    }

    /**
     * @throws AmountMustBePositive
     */
    private function checkAmount(int $amount): void
    {
        if ($amount <= 0) {
            throw new AmountMustBePositive();
        }
    }

    public function price(): Price
    {
        return $this->unitPrice->multiply($this->amount);
    }

    public function changeAmount(int $amount): void
    {
        $this->checkAmount($amount);
        $this->amount = $amount;
    }

    public function add(int $amount): void
    {
        $this->checkAmount($amount);
        $this->amount += $amount;
    }
}
