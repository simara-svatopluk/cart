<?php

namespace Simara\Cart\Infrastructure;

use Simara\Cart\Domain\CartRepository;

class MemoryCartRepositoryTest extends CartRepositoryTest
{

    protected function createRepository(): CartRepository
    {
        return new MemoryCartRepository();
    }
}
