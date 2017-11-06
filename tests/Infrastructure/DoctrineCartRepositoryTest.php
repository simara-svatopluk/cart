<?php

namespace Simara\Cart\Infrastructure;

use Doctrine\ORM\EntityManager;
use Simara\Cart\Domain\Cart;
use Simara\Cart\Domain\CartRepository;
use Simara\Cart\Domain\Item;
use Simara\Cart\Utils\EntityManagerFactory;

class DoctrineCartRepositoryTest extends CartRepositoryTest
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    protected function createRepository(): CartRepository
    {
        return new DoctrineCartRepository($this->entityManager);
    }

    protected function flush()
    {
        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    protected function setUp()
    {
        $this->entityManager = EntityManagerFactory::getSqliteMemoryEntityManager([Cart::class, Item::class]);
        parent::setUp();
    }
}
