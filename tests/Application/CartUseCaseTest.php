<?php
declare(strict_types=1);

namespace Simara\Cart\Application;

use PHPUnit\Framework\TestCase;
use Simara\Cart\Domain\Price;
use Simara\Cart\Domain\StaticPrices;
use Simara\Cart\Infrastructure\MemoryCartRepository;

final class CartUseCaseTest extends TestCase
{
	/**
	 * @var CartUseCase
	 */
	private $useCase;

	public function testFullScenarioSuccess(): void
	{
		$this->useCase->add('1', 'p1', 1);

		$detail = $this->useCase->detail('1');

		$this->assertGreaterThan(0, (float) $detail->getTotalPrice()->getWithVat());
		$this->assertCount(1, $detail->getItems());
	}

	protected function setUp()
	{
		parent::setUp();

		$this->useCase = new CartUseCase(
			new MemoryCartRepository(),
			$this->prices = new StaticPrices([
				'p1' => new Price('10.0'),
			])
		);
	}
}
