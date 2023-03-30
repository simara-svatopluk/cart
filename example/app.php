<?php

use Simara\Cart\Application\CartUseCase;
use Simara\Cart\Infrastructure\Symfony\ContainerBuilderFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$containerBuilder = ContainerBuilderFactory::create(
    pricesCsvPath: __DIR__ . '/prices.csv'
);

$useCase = $containerBuilder->get(CartUseCase::class);
assert($useCase instanceof CartUseCase);

$useCase->add('2a', 'p12345', 1);
var_dump($useCase->detail('2a'));
