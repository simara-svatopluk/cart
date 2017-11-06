<?php

namespace Simara\Cart\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Cart
{

    /**
     * @var string
     */
    private $id;

    /**
     * @var Collection|Item[]
     */
    private $items;

    public function __construct(string $id)
    {
        $this->id = $id;
        $this->items = new ArrayCollection();
    }

    public function add(string $productId, Price $unitPrice, int $amount = 1): void
    {
        try {
            $item = $this->find($productId);
            $item->add($amount);
        } catch (ProductNotInCartException $e) {
            $this->items->add(new Item($productId, $unitPrice, $amount));
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

    public function calculate(): CartDetail
    {
        $detailItems = $this->items->map(function (Item $item): ItemDetail {
            return $item->toDetail();
        })->getValues();

        $prices = $this->items->map(function (Item $item): Price {
            return $item->calculatePrice();
        })->getValues();

        $totalPrice = Price::sum($prices);

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
