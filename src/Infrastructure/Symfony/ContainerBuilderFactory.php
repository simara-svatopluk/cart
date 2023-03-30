<?php

declare(strict_types=1);

namespace Simara\Cart\Infrastructure\Symfony;

use Simara\Cart\Application\CartUseCase;
use Simara\Cart\Application\CartUseCaseApplication;
use Simara\Cart\Domain\Cart\CartRepository;
use Simara\Cart\Domain\Prices\Prices;
use Simara\Cart\Infrastructure\CsvPrices;
use Simara\Cart\Infrastructure\MemoryCartRepository;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ContainerBuilderFactory
{
    public static function create(
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
}
