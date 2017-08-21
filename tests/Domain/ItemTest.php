<?php

namespace Simara\Cart\Domain;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{

    public function testToDetail()
    {
        $item = new Item('x', new Price(5.0), 2);

        $expected = new DetailItem('x', new Price(5.0), 2);

        Assert::assertEquals($expected, $item->toDetail());
    }
}
