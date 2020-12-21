<?php

namespace Simara\Cart\Domain;

use Simara\Cart\Domain\Detail\CartDetail;

class Cart
{
    /**
     * @var array<string, ItemEntity>
     */
    private array $items = [];

    public function add(Item $item): void
    {
        try {
            $itemEntity = $this->find($item->getProductId());
            $itemEntity->add($item->getAmount());
        } catch (ProductNotInCart) {
            $this->items[$item->getProductId()] = new ItemEntity($item);
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
        $items = array_map(function (ItemEntity $item): Item {
            return $item->toItem();
        }, $this->items);

        $prices = array_map(function (ItemEntity $item): Price {
            return $item->toItem()->price();
        }, $this->items);

        $totalPrice = Price::sum($prices);

        return new CartDetail(array_values($items), $totalPrice);
    }

    /**
     * @throws ProductNotInCart
     */
    private function find(string $productId): ItemEntity
    {
        if (!isset($this->items[$productId])) {
			throw new ProductNotInCart();
		}
        return $this->items[$productId];
    }
}
