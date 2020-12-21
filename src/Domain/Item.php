<?php

namespace Simara\Cart\Domain;

use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Pure;

#[Immutable]
class Item
{
	/**
	 * @throws AmountMustBePositive
	 */
	public function __construct(
		private string $productId,
		private Price $unitPrice,
		private int $amount = 1
	)
	{
		$this->checkAmount($amount);
	}

	/**
	 * @throws AmountMustBePositive
	 */
	private function checkAmount(int $amount): void
	{
		if ($amount <= 0) {
			throw new AmountMustBePositive();
		}
	}

	public function getProductId(): string
	{
		return $this->productId;
	}

	public function getAmount(): int
	{
		return $this->amount;
	}

	public function getUnitPrice(): Price
	{
		return $this->unitPrice;
	}

	#[Pure]
	public function price(): Price
	{
		return $this->unitPrice->multiply($this->amount);
	}

	public function withAmount(int $amount): self
	{
		return new self($this->productId, $this->unitPrice, $amount);
	}

	public function add(int $amount): self
	{
		$this->checkAmount($amount);
		return new self($this->productId, $this->unitPrice, $amount + $this->amount);
	}
}
