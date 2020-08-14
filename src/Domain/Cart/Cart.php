<?php

namespace Simara\Cart\Domain\Cart;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Simara\Cart\Domain\Price;
use Simara\Cart\Domain\Prices\Prices;

class Cart
{
    private string $id;

    /**
     * @var Collection|Item[]
     */
    private $items;

    public function __construct(string $id)
    {
        $this->id = $id;
        $this->items = new ArrayCollection();
    }

    public function add(string $productId, int $amount = 1): void
    {
        try {
            $item = $this->find($productId);
            $item->add($amount);
        } catch (ProductNotInCartException $e) {
            $this->items->add(new Item($productId, $amount));
        }
    }

    /**
     * @throws ProductNotInCartException
     */
    public function remove(string $productId): void
    {
        $key = $this->findKey($productId);
        $this->items->remove($key);
    }

    /**
     * @throws ProductNotInCartException
     */
    public function changeAmount(string $productId, int $amount): void
    {
        $item = $this->find($productId);
        $item->changeAmount($amount);
    }

    public function calculate(Prices $prices): CartDetail
    {
        $detailItems = $this->items->map(fn(Item $item): ItemDetail => $item->toDetail($prices))->getValues();

        $itemPrices = $this->items->map(fn(Item $item): Price => $item->calculatePrice($prices))->getValues();

        $totalPrice = Price::sum($itemPrices);

        return new CartDetail(array_values($detailItems), $totalPrice);
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

    /**
     * @throws ProductNotInCartException
     */
    private function findKey(string $productId): string
    {
        foreach ($this->items as $key => $item) {
            if ($item->getProductId() === $productId) {
                return $key;
            }
        }
        throw new ProductNotInCartException();
    }

    public function getId(): string
    {
        return $this->id;
    }
}
