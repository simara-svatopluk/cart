<?php

namespace Simara\Cart\Application;

use Simara\Cart\Domain\Cart\CartDetail;

interface CartUseCase
{
    public function add(string $cartId, string $productId, int $amount): void;

    public function detail(string $cartId): CartDetail;
}