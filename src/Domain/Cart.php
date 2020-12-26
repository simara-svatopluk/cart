<?php

namespace Simara\Cart\Domain;

use Simara\Cart\Domain\Detail\CartDetail;
use Simara\Cart\Domain\Detail\ItemDetail;

final class Cart
{
    /**
     * @var array<string, Item>
     */
    private array $items = [];

    public function add(string $productId, Price $unitPrice, int $amount = 1): void
    {
        try {
            $itemEntity = $this->find($productId);
            $itemEntity->add($amount);
        } catch (ProductNotInCart) {
            $this->items[$productId] = new Item($productId, $unitPrice, $amount);
        }
    }

    /**
     * @throws ProductNotInCart
     */
    public function remove(string $productId): void
    {
        $this->find($productId);
        unset($this->items[$productId]);
    }

    /**
     * @throws ProductNotInCart
     */
    public function changeAmount(string $productId, int $amount): void
    {
        $item = $this->find($productId);
        $item->changeAmount($amount);
    }

    public function calculate(): CartDetail
    {
        $items = array_map(
            fn(Item $item): ItemDetail => $item->toDetail(),
            $this->items
        );

        $prices = array_map(
            fn(Item $item): Price => $item->price(),
            $this->items
        );

        $totalPrice = Price::sum($prices);

        return new CartDetail(array_values($items), $totalPrice);
    }

    /**
     * @throws ProductNotInCart
     */
    private function find(string $productId): Item
    {
        return $this->items[$productId] ?? throw new ProductNotInCart();
    }
}
