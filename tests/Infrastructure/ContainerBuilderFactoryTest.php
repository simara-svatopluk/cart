<?php

namespace Simara\Cart\Infrastructure;

use PHPUnit\Framework\TestCase;
use Simara\Cart\Application\CartUseCase;
use Simara\Cart\Infrastructure\Symfony\ContainerBuilderFactory;

final class ContainerBuilderFactoryTest extends TestCase
{
    public function testUseCaseCanBeObtained()
    {
        $containerBuilder = ContainerBuilderFactory::create(
            pricesCsvPath: __DIR__ . '/fixtures/prices.csv'
        );

        $useCase = $containerBuilder->get(CartUseCase::class);
        $this->assertInstanceOf(CartUseCase::class, $useCase);
    }
}
