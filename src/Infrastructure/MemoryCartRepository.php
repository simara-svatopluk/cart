<?php

namespace Simara\Cart\Infrastructure;

use Simara\Cart\Domain\Cart\Cart;
use Simara\Cart\Domain\Cart\CartNotFoundException;
use Simara\Cart\Domain\Cart\CartRepository;

final class MemoryCartRepository implements CartRepository
{
    /**
     * @var array<string, Cart>
     */
    private array $carts = [];

    public function add(Cart $cart): void
    {
        $this->carts[$cart->getId()] = $cart;
    }

    public function get(string $id): Cart
    {
        $this->checkExistence($id);
        return $this->carts[$id];
    }

    private function checkExistence(string $id): void
    {
        if (!isset($this->carts[$id])) {
            throw new CartNotFoundException();
        }
    }

    public function remove(string $id): void
    {
        $this->checkExistence($id);
        unset($this->carts[$id]);
    }
}
