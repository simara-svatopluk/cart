<?php

namespace Simara\Cart\Infrastructure;

use Simara\Cart\Domain\Cart\CartRepository;

class MemoryCartRepositoryTest extends CartRepositoryTest
{

    protected function createRepository(): CartRepository
    {
        return new MemoryCartRepository();
    }
}
