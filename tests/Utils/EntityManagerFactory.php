<?php

namespace Simara\Cart\Utils;

use Doctrine\DBAL\Driver\PDOSqlite\Driver;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\SimplifiedYamlDriver;
use Doctrine\ORM\Proxy\ProxyFactory;
use Doctrine\ORM\Tools\SchemaTool;

final class EntityManagerFactory
{
    public static function getSqliteMemoryEntityManager(array $schemaClassNames): EntityManager
    {
        $config = new Configuration();

        $namespaces = [
            __DIR__ . '/../../src/Infrastructure/DoctrineMapping' => 'Simara\\Cart\\Domain'
        ];
        $yamlDriver = new SimplifiedYamlDriver($namespaces, '.yml');

        $config->setMetadataDriverImpl($yamlDriver);

        $config->setProxyDir(__DIR__ . '/../Tests/Proxies');
        $config->setProxyNamespace('Doctrine\Tests\Proxies');
        $config->setAutoGenerateProxyClasses(ProxyFactory::AUTOGENERATE_NEVER);

        $entityManager = EntityManager::create(
            [
                'driverClass' => Driver::class,
                'memory' => true,
            ],
            $config
        );

        (new SchemaTool($entityManager))
            ->createSchema(array_map([$entityManager, 'getClassMetadata'], $schemaClassNames));

        return $entityManager;
    }
}
