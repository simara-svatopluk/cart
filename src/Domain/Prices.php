<?php
declare(strict_types=1);

namespace Simara\Cart\Domain;

interface Prices
{
	/**
	 * @throws PriceNotFoundException
	 */
	public function unitPrice(string $productId): Price;
}
