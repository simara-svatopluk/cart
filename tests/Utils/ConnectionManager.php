<?php

namespace Simara\Cart\Utils;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\PDO\PgSQL\Driver as PgSqlDriver;
use Doctrine\DBAL\Driver\PDO\MySQL\Driver as MySqlDriver;
use Doctrine\DBAL\Driver\PDO\SQLite\Driver as SqliteDriver;
use Exception;

final class ConnectionManager
{
    private static ?Connection $connectionForCreatingDatabases = null;

    public static function dropAndCreateDatabase(): void
    {
        if (self::$connectionForCreatingDatabases === null) {
            self::$connectionForCreatingDatabases = new Connection([
                'user' => self::getUser(),
                'password' => self::getPassword(),
                'host' => self::getHost(),
            ], self::getDriver());
        }

        self::$connectionForCreatingDatabases->exec(sprintf('DROP DATABASE IF EXISTS %s', self::getDbName()));
        self::$connectionForCreatingDatabases->exec(sprintf('CREATE DATABASE %s', self::getDbName()));
    }

    private static function getUser(): ?string
    {
        return $GLOBALS['DB_USER'] ?? null;
    }

    private static function getPassword(): ?string
    {
        return $GLOBALS['DB_PASSWORD'] ?? null;
    }

    private static function getHost(): ?string
    {
        return $GLOBALS['DB_HOST'] ?? null;
    }

    private static function getDriver(): Driver
    {
        if (!isset($GLOBALS['DB_DRIVER'])) {
            throw new Exception('Please set at least DB_DRIVER');
        }

        if ($GLOBALS['DB_DRIVER'] === 'pdo_pgsql') {
            return new PgSqlDriver();
        }

        if ($GLOBALS['DB_DRIVER'] === 'pdo_mysql') {
            return new MySqlDriver();
        }

        throw new Exception('Driver not supported');
    }

    private static function getDbName(): ?string
    {
        return $GLOBALS['DB_DBNAME'] ?? null;
    }

    public static function createConnection(): Connection
    {
        return new Connection([
            'user' => self::getUser(),
            'password' => self::getPassword(),
            'dbname' => self::getDbName(),
            'host' => self::getHost(),
        ], self::getDriver());
    }

    public static function createSqliteMemoryConnection(): Connection
    {
        return new Connection([
            'memory' => true,
        ], new SqliteDriver());
    }
}
