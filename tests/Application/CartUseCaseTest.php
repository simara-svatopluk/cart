<?php

declare(strict_types=1);

namespace Simara\Cart\Application;

use PHPUnit\Framework\TestCase;
use Simara\Cart\Domain\Price;
use Simara\Cart\Domain\Cart\StaticPrices;
use Simara\Cart\Infrastructure\MemoryCartRepository;

final class CartUseCaseTest extends TestCase
{
    private CartUseCaseApplication $useCase;

    public function testFullScenarioSuccess(): void
    {
        $this->useCase->add('1', 'p1', 1);

        $detail = $this->useCase->detail('1');

        $this->assertGreaterThan(0, (float) $detail->getTotalPrice()->getWithVat());
        $this->assertCount(1, $detail->getItems());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->useCase = new CartUseCaseApplication(
            new MemoryCartRepository(),
            new StaticPrices([
                'p1' => new Price('10.0'),
            ])
        );
    }
}
