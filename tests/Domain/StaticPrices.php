<?php
declare(strict_types=1);

namespace Simara\Cart\Domain;

final class StaticPrices implements Prices
{
	/**
	 * @var Price[]|array<string, Price>
	 */
	private $prices;

	public function __construct($prices)
	{
		$this->prices = $prices;
	}

	public function unitPrice(string $productId): Price
	{
		return $this->prices[$productId];
	}
}
