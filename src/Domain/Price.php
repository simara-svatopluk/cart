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

    public function getWithVat(): float
    {
        return $this->withVat;
    }
}
