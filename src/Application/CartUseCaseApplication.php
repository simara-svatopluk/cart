<?php

declare(strict_types=1);

namespace Simara\Cart\Application;

use Simara\Cart\Domain\Cart\Cart;
use Simara\Cart\Domain\Cart\CartDetail;
use Simara\Cart\Domain\Cart\CartNotFoundException;
use Simara\Cart\Domain\Cart\CartRepository;
use Simara\Cart\Domain\Prices\Prices;

final class CartUseCaseApplication implements CartUseCase
{
    public function __construct(
        private CartRepository $repository,
        private Prices $prices
    ) {
    }

    public function add(string $cartId, string $productId, int $amount): void
    {
        $cart = $this->get($cartId);
        $cart->add($productId, $amount);
        $this->repository->add($cart);
    }

    public function detail(string $cartId): CartDetail
    {
        return $this->get($cartId)->calculate($this->prices);
    }

    private function get(string $cartId): Cart
    {
        try {
            return $this->repository->get($cartId);
        } catch (CartNotFoundException $e) {
            return new Cart($cartId);
        }
    }
}
