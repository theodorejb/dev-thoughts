<?php

declare(strict_types=1);

namespace theodorejb\DevThoughts\Test;

use Exception;
use mysqli;

class DbConnector
{
    private static Config $config;
    private static ?mysqli $mysqlConn = null;

    /**
     * @var resource|null
     */
    private static $sqlsrvConn;

    public static function setConfig(Config $config): void
    {
        self::$config = $config;
    }

    public static function getConfig(): Config
    {
        return self::$config;
    }

    public static function getMysqlConn(): mysqli
    {
        if (!self::$mysqlConn) {
            $c = self::getConfig();
            $dbPort = getenv('DB_PORT');

            if ($dbPort === false) {
                $dbPort = 3306;
            } else {
                $dbPort = (int) $dbPort;
            }

            self::$mysqlConn = new mysqli($c->getMysqlHost(), $c->getMysqlUser(), $c->getMysqlPassword(), $c->getMysqlDatabase(), $dbPort);

            if (self::$mysqlConn->connect_error) {
                throw new Exception('Failed to connect to MySQL: (' . self::$mysqlConn->connect_errno . ') ' . self::$mysqlConn->connect_error);
            }
        }

        return self::$mysqlConn;
    }

    /**
     * @return resource
     */
    public static function getSqlsrvConn()
    {
        if (!self::$sqlsrvConn) {
            $c = self::getConfig();
            self::$sqlsrvConn = sqlsrv_connect($c->getSqlsrvServer(), $c->getSqlsrvConnInfo());

            if (!self::$sqlsrvConn) {
                throw new Exception('Failed to connect to SQL server: ' . print_r(sqlsrv_errors(), true));
            }
        }

        return self::$sqlsrvConn;
    }

    public static function deleteTestTable(): void
    {
        $sql = "DROP TABLE dev_thoughts";

        if (self::$mysqlConn) {
            if (!self::$mysqlConn->query($sql)) {
                throw new Exception('Failed to drop MySQL test table: ' . print_r(self::$mysqlConn->error_list, true));
            }
        }

        if (self::$sqlsrvConn) {
            if (!sqlsrv_query(self::$sqlsrvConn, $sql)) {
                throw new Exception('Failed to drop SQL Server test table: ' . print_r(sqlsrv_errors(), true));
            }
        }
    }
}
