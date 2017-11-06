<?php

namespace Simara\Cart\Domain;

interface PriceAdapter
{

    /**
     * @throws PriceNotFoundException
     */
    public function getUnitPrice(string $productId): Price;
}
