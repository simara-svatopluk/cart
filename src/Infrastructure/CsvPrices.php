<?php

declare(strict_types=1);

namespace Simara\Cart\Infrastructure;

use Simara\Cart\Domain\Price;
use Simara\Cart\Domain\Prices\PriceNotFoundException;
use Simara\Cart\Domain\Prices\Prices;

use function fopen;
use function is_resource;

final class CsvPrices implements Prices
{
    /**
     * @var array<string, Price>
     */
    private array $prices = [];

    public function __construct(private string $filename)
    {
    }

    public function unitPrice(string $productId): Price
    {
        $this->loadPrices();
        return $this->prices[$productId] ?? throw new PriceNotFoundException();
    }

    private function loadPrices(): void
    {
        if ($this->prices !== []) {
            return;
        }

        $handle = fopen($this->filename, 'r');
        assert(is_resource($handle));
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            $id = $data[0];
            $price = new Price($data[1]);
            $this->prices[$id] = $price;
        }
        fclose($handle);
    }
}
