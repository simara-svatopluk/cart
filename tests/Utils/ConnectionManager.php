<?php

namespace Simara\Cart\Utils;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOPgSql\Driver as PgSqlDriver;
use Doctrine\DBAL\Driver\PDOMySql\Driver as MySqlDriver;

class ConnectionManager
{

    /**
     * @var Connection
     */
    private static $connectionForCreatingDatabases;

    public static function dropAndCreateDatabase()
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

    private static function getUser()
    {
        return $GLOBALS['DB_USER'] ?? null;
    }

    private static function getPassword()
    {
        return $GLOBALS['DB_PASSWORD'] ?? null;
    }

    private static function getHost()
    {
        return $GLOBALS['DB_HOST'] ?? null;
    }

    private static function getDriver()
    {
        if (!isset($GLOBALS['DB_DRIVER'])) {
            return null;
        }

        if ($GLOBALS['DB_DRIVER'] === 'pdo_pgsql') {
            return new PgSqlDriver();
        }

        if ($GLOBALS['DB_DRIVER'] === 'pdo_mysql') {
            return new MySqlDriver();
        }
    }

    private static function getDbName()
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
}
