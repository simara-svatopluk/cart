<?php
declare(strict_types=1);

namespace Simara\Cart\Domain;

interface Prices
{
	public function unitPrice(string $productId): Price;
}
