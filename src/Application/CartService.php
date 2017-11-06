<?php

namespace Simara\Cart\Application;

use Simara\Cart\Domain\Cart;
use Simara\Cart\Domain\CartDetail;
use Simara\Cart\Domain\CartRepository;
use Simara\Cart\Domain\Price;
use Simara\Cart\Domain\PriceAdapter;
use Simara\Cart\Domain\PriceNotFoundException;

class CartService
{

    /**
     * @var PriceAdapter
     */
    private $priceAdapter;

    /**
     * @var CartRepository
     */
    private $repository;

    public function __construct(PriceAdapter $priceAdapter, CartRepository $repository)
    {
        $this->priceAdapter = $priceAdapter;
        $this->repository = $repository;
    }


    public function create(string $id)
    {
        $cart = new Cart($id);
        $this->repository->add($cart);
    }

    public function addProduct(string $id, string $productId, int $amount = 1)
    {
        $price = $this->tryGeUnitPrice($productId);

        $cart = $this->repository->get($id);
        $cart->add($productId, $price, $amount);
    }

    private function tryGeUnitPrice(string $productId): Price
    {
        try {
            return $this->priceAdapter->getUnitPrice($productId);
        } catch (PriceNotFoundException $e) {
            throw new ProductCannotBeBoughtException();
        }
    }

    public function changeAmount(string $id, string $productId, int $amount)
    {
        $cart = $this->repository->get($id);
        $cart->changeAmount($productId, $amount);
    }

    public function removeProduct(string $id, string $productId)
    {
        $cart = $this->repository->get($id);
        $cart->remove($productId);
    }

    public function remove(string $id)
    {
        $this->repository->remove($id);
    }

    public function calculate(string $id): CartDetail
    {
        $cart = $this->repository->get($id);
        return $cart->calculate();
    }
}
