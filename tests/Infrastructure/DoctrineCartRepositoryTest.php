<?php

namespace Simara\Cart\Infrastructure;

use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\Assert;
use Simara\Cart\Domain\Cart;
use Simara\Cart\Domain\CartRepository;
use Simara\Cart\Domain\Item;
use Simara\Cart\Domain\Price;
use Simara\Cart\Infrastructure\DoctrineMapping\PriceType;
use Simara\Cart\Utils\EntityManagerFactory;
use Simara\Cart\Utils\ConnectionManager;

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

    protected function flush(): void
    {
        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    protected function setUp()
    {
        ConnectionManager::dropAndCreateDatabase();
        $connection = ConnectionManager::createConnection();
        $xmlMappedClasses = [Cart::class, Item::class];
        $types = [PriceType::class => PriceType::NAME];
        $this->entityManager = EntityManagerFactory::createEntityManager($connection, $xmlMappedClasses, $types);
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->entityManager->getConnection()->close();
    }

    public function testItemsAreRemovedWithCart()
    {
        $cart = new Cart('1');
        $cart->add('1', new Price(10), 1);
        $repository = $this->createRepository();
        $repository->add($cart);
        $this->flush();

        $repository->remove('1');
        $this->flush();

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from(Item::class, 'i')
            ->select('i');
        $query = $queryBuilder->getQuery();
        $result = $query->getResult();
        Assert::assertCount(0, $result);
    }
}
