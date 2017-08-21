<?php

namespace Simara\Cart\Domain;

class Price
{

    /**
     * @var float
     */
    private $withVat;

    public function __construct(float $withVat)
    {
        $this->withVat = $withVat;
    }

    /**
     * @param self[] $prices
     * @return self
     */
    public static function sum(array $prices): self
    {
        return array_reduce($prices, function (self $carry, self $price) {
            return $carry->add($price);
        }, new self(0.0));
    }

    public function getWithVat(): float
    {
        return $this->withVat;
    }

    public function add(self $adder): self
    {
        $withVat = $this->withVat + $adder->withVat;
        return new self($withVat);
    }

    public function multiply(int $multiplier): self
    {
        $withVat = $this->withVat * $multiplier;
        return new self($withVat);
    }
}
