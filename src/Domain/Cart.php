<?php

namespace Simara\Cart\Domain;

class Cart
{

    /**
     * @var Item[]
     */
    private $items = [];

    public function add(string $productId, Price $unitPrice, int $amount = 1)
    {
        try {
            $item = $this->find($productId);
            $item->add($amount);
        } catch (ProductNotInCartException $e) {
            $this->items[] = new Item($productId, $unitPrice, $amount);
        }
    }

    public function remove(string $productId)
    {
    }

    public function changeAmount(string $productId, int $amount)
    {
    }

    public function calculate(): CartDetail
    {
        $detailItems = array_map(function (Item $item): DetailItem {
            return $item->toDetail();
        }, $this->items);

        $totalPrice = array_reduce($detailItems, function (float $carry, DetailItem $item) {
            return $carry + $item->getPrice()->getWithVat() * $item->getAmount();
        }, 0.0);

        return new CartDetail($detailItems, new Price($totalPrice));
    }

    /**
     * @throws ProductNotInCartException
     */
    private function find(string $productId): Item
    {
        foreach ($this->items as $item) {
            if ($item->getProductId() === $productId) {
                return $item;
            }
        }
        throw new ProductNotInCartException();
    }
}
