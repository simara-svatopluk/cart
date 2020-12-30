<?php

namespace Simara\Cart\Domain\Cart;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Simara\Cart\Domain\Price;
use Simara\Cart\Domain\Prices\Prices;

final class Cart
{
    /**
     * @var Collection<string, Item>
     */
    private $items;

    public function __construct(private string $id)
    {
        $this->items = new ArrayCollection();
    }

    public function add(string $productId, int $amount = 1): void
    {
        try {
            $item = $this->find($productId);
            $item->add($amount);
        } catch (ProductNotInCartException) {
            $this->items->set($productId, new Item($productId, $amount));
        }
    }

    /**
     * @throws ProductNotInCartException
     */
    public function remove(string $productId): void
    {
        $this->items->remove($productId);
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
        $item = $this->items->get($productId);

        if ($item instanceof Item) {
            return $item;
        }
        throw new ProductNotInCartException();
    }

    public function getId(): string
    {
        return $this->id;
    }
}
