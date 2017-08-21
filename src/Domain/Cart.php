<?php

namespace Simara\Cart\Domain;

class Cart
{

    public function add(string $productId, Price $unitPrice, int $amount = 1)
    {
    }

    public function remove(string $productId)
    {
    }

    public function changeAmount(string $productId, int $amount)
    {
    }

    public function calculate(): CartDetail
    {
        return new CartDetail([], new Price(0.0));
    }
}
