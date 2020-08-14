<?php
declare(strict_types=1);

namespace Simara\Cart\Domain\Cart;

use Simara\Cart\Domain\Price;
use Simara\Cart\Domain\Prices\Prices;

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
