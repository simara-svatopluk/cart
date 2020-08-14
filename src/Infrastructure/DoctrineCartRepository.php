<?php

namespace Simara\Cart\Infrastructure;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Simara\Cart\Domain\Cart\Cart;
use Simara\Cart\Domain\Cart\CartNotFoundException;
use Simara\Cart\Domain\Cart\CartRepository;
use TypeError;

class DoctrineCartRepository implements CartRepository
{
    private EntityManager $entityManger;

    public function __construct(EntityManager $entityManger)
    {
        $this->entityManger = $entityManger;
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
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            throw new CartNotFoundException();
        }
    }

    public function remove(string $id): void
    {
        $cart = $this->get($id);
        $this->entityManger->remove($cart);
    }
}
