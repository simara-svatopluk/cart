<?php

declare(strict_types=1);

namespace Simara\Cart\Infrastructure\DoctrineMapping;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Exception;
use Simara\Cart\Domain\Price;

class PriceType extends Type
{
    const NAME = 'priceType';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'DECIMAL(8,2)';
    }
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }
        return new Price($value);
    }
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!$value instanceof Price) {
            throw new Exception('Value must be type of Price');
        }
        return $value->getWithVat();
    }
    public function getName()
    {
        self::NAME;
    }
}
