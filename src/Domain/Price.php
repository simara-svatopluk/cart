<?php

namespace Simara\Cart\Domain;

use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Pure;

#[Immutable]
class Price
{
	public function __construct(private int $withVat)
	{
	}

	/**
	 * @param self[] $prices
	 * @return self
	 */
	public static function sum(array $prices): self
	{
		return array_reduce($prices, function (self $carry, self $price) {
			return $carry->add($price);
		}, new self(0));
	}

	public function getWithVat(): int
	{
		return $this->withVat;
	}

	#[Pure]
	public function add(self $adder): self
	{
		$withVat = $this->withVat + $adder->withVat;
		return new self($withVat);
	}

	#[Pure]
	public function multiply(int $multiplier): self
	{
		$withVat = $this->withVat * $multiplier;
		return new self($withVat);
	}
}
