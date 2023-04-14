<?php

declare(strict_types=1);

namespace Simara\Cart\Infrastructure\Symfony;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Simara\Cart\Application\CartUseCase;
use Simara\Cart\Application\CartUseCaseApplication;
use Simara\Cart\Domain\Cart\Cart;
use Simara\Cart\Domain\Cart\CartRepository;
use Simara\Cart\Domain\Cart\Item;
use Simara\Cart\Domain\Prices\Prices;
use Simara\Cart\Infrastructure\CsvPrices;
use Simara\Cart\Infrastructure\DoctrineCartRepository;
use Simara\Cart\Infrastructure\MemoryCartRepository;
use Simara\Cart\Utils\ConnectionManager;
use Simara\Cart\Utils\EntityManagerFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ContainerBuilderFactory
{
    public static function createInMemory(
        string $pricesCsvPath,
    ): ContainerBuilder {
        $containerBuilder = new ContainerBuilder();

        $containerBuilder
            ->register(CartRepository::class, MemoryCartRepository::class);

        $containerBuilder
            ->register(Prices::class, CsvPrices::class)
            ->addArgument($pricesCsvPath);

        $containerBuilder
            ->register(CartUseCase::class, CartUseCaseApplication::class)
            ->setAutowired(true)
            ->setPublic(true);

        $containerBuilder->compile();

        return $containerBuilder;
    }
    public static function create(
        string $pricesCsvPath,
    ): ContainerBuilder {
        $containerBuilder = new ContainerBuilder();

        $containerBuilder
            ->register(Connection::class, Connection::class)
            ->setFactory([ConnectionManager::class, 'createConnection']);

        $containerBuilder
            ->register(EntityManager::class, EntityManager::class)
            ->setFactory([EntityManagerFactory::class, 'createEntityManager'])
            ->setArgument('$schemaClassNames', [Cart::class, Item::class])
            ->setArgument('$types', [])
            ->setAutowired(true);

        $containerBuilder
            ->register(CartRepository::class, DoctrineCartRepository::class)
            ->setAutowired(true);

        $containerBuilder
            ->register(Prices::class, CsvPrices::class)
            ->addArgument($pricesCsvPath);

        $containerBuilder
            ->register(CartUseCase::class, CartUseCaseApplication::class)
            ->setAutowired(true)
            ->setPublic(true);

        $containerBuilder->compile();

        return $containerBuilder;
    }
}
