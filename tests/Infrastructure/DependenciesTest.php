<?php

namespace Simara\Cart\Infrastructure;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Simara\Cart\Application\CartUseCase;
use Simara\Cart\Infrastructure\Symfony\ContainerBuilderFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class DependenciesTest extends TestCase
{
    public static function containerBuilders()
    {
        yield [ContainerBuilderFactory::createInMemory(
            pricesCsvPath: __DIR__ . '/fixtures/prices.csv'
        )];
        yield [ContainerBuilderFactory::create(
            pricesCsvPath: __DIR__ . '/fixtures/prices.csv'
        )];
    }

    #[DataProvider('containerBuilders')]
    public function testUseCaseCanBeObtained(ContainerBuilder $containerBuilder)
    {
        $useCase = $containerBuilder->get(CartUseCase::class);
        $this->assertInstanceOf(CartUseCase::class, $useCase);
    }
}
