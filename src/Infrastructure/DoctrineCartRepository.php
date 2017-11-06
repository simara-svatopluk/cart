<?php

namespace Simara\Cart\Infrastructure;

use Doctrine\ORM\EntityManager;
use Simara\Cart\Domain\Cart;
use Simara\Cart\Domain\CartNotFoundException;
use Simara\Cart\Domain\CartRepository;
use TypeError;

class DoctrineCartRepository implements CartRepository
{

    /**
     * @var EntityManager
     */
    private $entityManger;

    public function __construct(EntityManager $entityManger)
    {
        $this->entityManger = $entityManger;
    }

    public function add(Cart $cart): void
    {
        $this->entityManger->persist($cart);
    }

    private function find(string $id)
    {
        return $this->entityManger->find(Cart::class, $id);
    }

    public function get(string $id): Cart
    {
        return $this->getThrowingException($id);
    }

    private function getThrowingException(string $id): Cart
    {
        try {
            return $this->find($id);
        } catch (TypeError $e) {
            throw new CartNotFoundException();
        }
    }

    public function remove(string $id): void
    {
        $cart = $this->getThrowingException($id);
        $this->entityManger->remove($cart);
    }
}
