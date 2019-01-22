<?php

declare(strict_types=1);

namespace Simara\Cart\Infrastructure\DoctrineMapping;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Simara\Cart\Domain\Price;

class PriceType extends Type
{
    const NAME = 'priceType';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'FLOAT';
    }
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new Price((float) $value);
    }
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof Price) {
            return (string) $value->getWithVat();
        }
        return $value;
    }
    public function getName()
    {
        self::NAME;
    }
}
