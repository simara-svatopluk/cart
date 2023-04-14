<?php

namespace Simara\Cart\Infrastructure;

use PHPUnit\Framework\TestCase;
use Simara\Cart\Application\CartUseCase;
use Simara\Cart\Infrastructure\Symfony\ContainerBuilderFactory;

final class DependenciesTest extends TestCase
{
    public function testUseCaseCanBeObtained()
    {
        $containerBuilder = ContainerBuilderFactory::createInMemory(
            pricesCsvPath: __DIR__ . '/fixtures/prices.csv'
        );

        $useCase = $containerBuilder->get(CartUseCase::class);
        $this->assertInstanceOf(CartUseCase::class, $useCase);
    }
}
