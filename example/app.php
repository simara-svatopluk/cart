<?php

use Simara\Cart\Application\CartUseCase;
use Simara\Cart\Infrastructure\Symfony\ContainerBuilderFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$containerBuilder = ContainerBuilderFactory::createInMemory(
    pricesCsvPath: __DIR__ . '/prices.csv'
);

$useCase = $containerBuilder->get(CartUseCase::class);
assert($useCase instanceof CartUseCase);

// Just a demo
$useCase->add('2a', 'p12345', 1);
var_dump($useCase->detail('2a'));
