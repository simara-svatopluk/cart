<?php

namespace Simara\Cart\Utils;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver;
use Doctrine\ORM\Proxy\ProxyFactory;
use Doctrine\ORM\Tools\SchemaTool;

final class EntityManagerFactory
{
    public static function createEntityManager(
        Connection $connection,
        array $schemaClassNames,
        array $types
    ): EntityManager {
        $config = new Configuration();

        $namespaces = [
            __DIR__ . '/../../src/Infrastructure/DoctrineMapping' => 'Simara\\Cart\\Domain\\Cart'
        ];
        $xmlDrive = new SimplifiedXmlDriver($namespaces, '.xml');

        $config->setMetadataDriverImpl($xmlDrive);

        $config->setProxyDir(__DIR__);
        $config->setProxyNamespace('Doctrine\Tests\Proxies');
        $config->setAutoGenerateProxyClasses(ProxyFactory::AUTOGENERATE_NEVER);

        $platform = $connection->getDatabasePlatform();
        foreach ($types as $class => $name) {
            self::registerType($platform, $class, $name);
        }
        $entityManager = EntityManager::create($connection, $config);

        (new SchemaTool($entityManager))
            ->createSchema(array_map([$entityManager, 'getClassMetadata'], $schemaClassNames));

        return $entityManager;
    }

    private static function registerType(AbstractPlatform $platform, string $class, string $name)
    {
        if (!Type::hasType($name)) {
            Type::addType($name, $class);
        }
        $platform->registerDoctrineTypeMapping($name, $name);
    }
}
