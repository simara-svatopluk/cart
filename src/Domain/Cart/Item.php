<?php

namespace Simara\Cart\Domain\Cart;

use Simara\Cart\Domain\Price;
use Simara\Cart\Domain\Prices\Prices;

final class Item
{
    private int $generatedId;

    private string $productId;

    private int $amount;

    /**
     * @throws AmountMustBePositiveException
     */
    public function __construct(string $productId, int $amount)
    {
        $this->checkAmount($amount);
        $this->productId = $productId;
        $this->amount = $amount;
    }

    public function toDetail(Prices $prices): ItemDetail
    {
        return new ItemDetail($this->productId, $prices->unitPrice($this->productId), $this->amount);
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * @throws AmountMustBePositiveException
     */
    public function add(int $amount): void
    {
        $this->checkAmount($amount);
        $this->amount = $this->amount + $amount;
    }

    /**
     * @throws AmountMustBePositiveException
     */
    private function checkAmount(int $amount): void
    {
        if ($amount <= 0) {
            throw new AmountMustBePositiveException();
        }
    }

    /**
     * @throws AmountMustBePositiveException
     */
    public function changeAmount(int $amount): void
    {
        $this->checkAmount($amount);
        $this->amount = $amount;
    }

    public function calculatePrice(Prices $prices): Price
    {
        return $prices->unitPrice($this->productId)->multiply($this->amount);
    }
}
