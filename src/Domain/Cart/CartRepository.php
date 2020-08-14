<?php

namespace Simara\Cart\Domain\Cart;

interface CartRepository
{

    public function add(Cart $cart): void;

    /**
     * @throws CartNotFoundException
     */
    public function get(string $id): Cart;

    /**
     * @throws CartNotFoundException
     */
    public function remove(string $id): void;
}
