<?php

namespace Simara\Cart\Infrastructure;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Simara\Cart\Domain\Cart\Cart;
use Simara\Cart\Domain\Cart\CartNotFoundException;
use Simara\Cart\Domain\Cart\CartRepository;
use TypeError;

use function assert;

final class DoctrineCartRepository implements CartRepository
{
    public function __construct(private EntityManager $entityManger)
    {
    }

    public function add(Cart $cart): void
    {
        $this->entityManger->persist($cart);
    }

    public function get(string $id): Cart
    {
        $queryBuilder = $this->entityManger->createQueryBuilder();
        $queryBuilder
            ->select('cart, items')
            ->from(Cart::class, 'cart')
            ->leftJoin('cart.items', 'items')
            ->where('cart.id = :id')
            ->setParameter(':id', $id);

        $query = $queryBuilder->getQuery();

        try {
            $cart = $query->getSingleResult();
            assert($cart instanceof Cart);
            return $cart;
        } catch (NoResultException) {
            throw new CartNotFoundException();
        }
    }

    public function remove(string $id): void
    {
        $cart = $this->get($id);
        $this->entityManger->remove($cart);
    }
}
