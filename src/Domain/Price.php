<?php

namespace Simara\Cart\Domain;

use Money\Money;
use Money\Number;

class Price
{
    private const EURO_TO_CENTS_CONVERTING_BASE = -2;
    private const CENTS_TO_EURO_CONVERTING_BASE = 2;

    /**
     * @var Money
     */
    private $withVat;

    public function __construct(string $withVat)
    {
        $cents = $this->eurToCents($withVat);
        $this->withVat = Money::EUR($cents);
    }

    /**
     * @param self[] $prices
     * @return self
     */
    public static function sum(array $prices): self
    {
        return array_reduce($prices, function (self $carry, self $price) {
            return $carry->add($price);
        }, new self('0'));
    }

    public function getWithVat(): string
    {
        return $this->centsToEurs($this->withVat->getAmount());
    }

    public function add(self $adder): self
    {
        $withVat = $this->withVat->add($adder->withVat);
        return self::fromMoney($withVat);
    }

    public function multiply(int $multiplier): self
    {
        $withVat = $this->withVat->multiply($multiplier);
        return self::fromMoney($withVat);
    }

    private static function fromMoney(Money $withVat): self
    {
        $price = new self('0');
        $price->withVat = $withVat;
        return $price;
    }

    private function eurToCents(string $euros): string
    {
        $number = Number::fromString($euros);
        return $number->base10(self::EURO_TO_CENTS_CONVERTING_BASE)->getIntegerPart();
    }

    private function centsToEurs(string $cents): string
    {
        $number = new Number($cents);
        $inEuro = $number->base10(self::CENTS_TO_EURO_CONVERTING_BASE);
        if ($inEuro->isInteger()) {
            return $inEuro->getIntegerPart();
        } else {
            return $inEuro->getIntegerPart() . '.' . $inEuro->getFractionalPart();
        }
    }
}
