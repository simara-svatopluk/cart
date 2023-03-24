<?php

declare(strict_types=1);

namespace Simara\Cart\Application;

use PHPUnit\Framework\TestCase;
use Simara\Cart\Domain\Cart\Cart;
use Simara\Cart\Domain\Cart\Item;
use Simara\Cart\Infrastructure\CsvPrices;
use Simara\Cart\Infrastructure\DoctrineCartRepository;
use Simara\Cart\Utils\ConnectionManager;
use Simara\Cart\Utils\EntityManagerFactory;

final class DependenciesTest extends TestCase
{
    public function testDependenciesCanBeBuildWithRealImplementation(): void
    {
        ConnectionManager::dropAndCreateDatabase();
        $connection = ConnectionManager::createConnection();
        $xmlMappedClasses = [Cart::class, Item::class];
        $entityManager = EntityManagerFactory::createEntityManager($connection, $xmlMappedClasses, []);

        $repository = new DoctrineCartRepository($entityManager);
        $prices = new CsvPrices('/dev/null');

        $useCase = new CartUseCaseApplication($repository, $prices);

        $this->assertInstanceOf(CartUseCaseApplication::class, $useCase);
        $connection->close();
    }
}
