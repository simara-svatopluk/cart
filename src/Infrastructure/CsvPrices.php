<?php
declare(strict_types=1);

namespace Simara\Cart\Infrastructure;

use Simara\Cart\Domain\Price;
use Simara\Cart\Domain\PriceNotFoundException;
use Simara\Cart\Domain\Prices;

final class CsvPrices implements Prices
{
	/**
	 * @var Price[]|array<string, Price>
	 */
	private $prices;

	public function __construct(string $filename)
	{
		$handle = \fopen($filename, 'r');
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$id = $data[0];
			$price = new Price($data[1]);
			$this->prices[$id] = $price;
		}
		fclose($handle);
	}

	public function unitPrice(string $productId): Price
	{
		if (!isset ($this->prices[$productId])) {
			throw new PriceNotFoundException();
		}
		return $this->prices[$productId];
	}
}
