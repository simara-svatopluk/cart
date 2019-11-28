<?php
declare(strict_types=1);

namespace Simara\Cart\Domain;

final class ConstantPrices implements Prices
{
	private Price $price;

	public function __construct(Price $price)
	{
		$this->price = $price;
	}

	public function unitPrice(string $productId): Price
	{
		return $this->price;
	}
}
