<?php

declare(strict_types=1);

namespace theodorejb\DevThoughts\Test;

use PeachySQL\{Mysql, PeachySql, SqlServer};
use PHPUnit\Framework\TestCase;
use theodorejb\DevThoughts\DevThoughts;
use theodorejb\DevThoughts\Test\src\DbConnector;

class DevThoughtsTest extends TestCase
{
    public static function tearDownAfterClass(): void
    {
        DbConnector::deleteTestTable();
    }

    /**
     * @return list<array{0: PeachySql}>
     */
    public static function dbProvider(): array
    {
        $config = DbConnector::getConfig();
        $databases = [];

        if ($config->testMysql()) {
            $databases[] = [new Mysql(DbConnector::getMysqlConn())];
        }

        if ($config->testSqlsrv()) {
            $databases[] = [new SqlServer(DbConnector::getSqlsrvConn())];
        }

        return $databases;
    }

    /**
     * @dataProvider dbProvider
     */
    public function testGetFeaturedThought(PeachySql $db): void
    {
        $devThoughts = new DevThoughts($db);
        $devThoughts->insertDefaultThoughts();
        $featured = $devThoughts->getFeaturedThought();
        $this->assertNotSame('', $featured->text);
    }
}
