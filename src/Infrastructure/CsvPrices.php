<?php
declare(strict_types=1);

namespace Simara\Cart\Infrastructure;

use Simara\Cart\Domain\Price;
use Simara\Cart\Domain\Prices\PriceNotFoundException;
use Simara\Cart\Domain\Prices\Prices;

final class CsvPrices implements Prices
{
	private string $filename;

	/**
	 * @var Price[]|array<string, Price>
	 */
	private array $prices = [];

	public function __construct(string $filename)
	{
		$this->filename = $filename;
	}

	public function unitPrice(string $productId): Price
	{
		$this->loadPrices();
		if (!isset ($this->prices[$productId])) {
			throw new PriceNotFoundException();
		}
		return $this->prices[$productId];
	}

	private function loadPrices()
	{
		if ($this->prices === []) {
			$handle = \fopen($this->filename, 'r');
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$id = $data[0];
				$price = new Price($data[1]);
				$this->prices[$id] = $price;
			}
			fclose($handle);
		}
	}
}
