<?php

declare(strict_types=1);

namespace theodorejb\DevThoughts;

use DateTimeImmutable;
use DateTimeZone;
use PeachySQL\PeachySql;
use PeachySQL\SqlServer;

/**
 * @psalm-type ThoughtRow = array{
 *     thought_id: int, thought: string, author: string, reference: string, last_featured: string|null
 * }
 */
class DevThoughts
{
    private const DB_DATE = 'Y-m-d H:i:s';

    public function __construct(
        private PeachySql $db,
        private string $table = 'dev_thoughts',
    ) {
    }

    /**
     * @param positive-int $featuredSeconds
     */
    public function getFeaturedThought(int $featuredSeconds = 60 * 60 * 24): Thought
    {
        $utc = new DateTimeZone('UTC');
        $start = new DateTimeImmutable("{$featuredSeconds} seconds ago", $utc);
        $sql = "SELECT thought_id, thought, author, reference, last_featured FROM {$this->table}";

        /** @var null|ThoughtRow $row */
        $row = $this->db->selectFrom($sql)
            ->where(['last_featured' => ['gt' => $start->format(self::DB_DATE)]])
            ->orderBy(['last_featured' => 'desc'])
            ->offset(0, 1)
            ->query()->getFirst();

        if ($row !== null) {
            return Thought::fromDbRow($row);
        }

        // select row that hasn't been featured for longest as new featured thought

        /** @var null|ThoughtRow $row */
        $row = $this->db->selectFrom($sql)
            ->orderBy(['last_featured' => 'asc'])
            ->offset(0, 1)
            ->query()->getFirst();

        if ($row === null) {
            throw new \Exception("No dev thoughts have been populated");
        }

        $thought = Thought::fromDbRow($row);

        $now = new DateTimeImmutable('now', $utc);
        $set = ['last_featured' => $now->format(self::DB_DATE)];
        $this->db->updateRows($this->table, $set, ['thought_id' => $thought->id]);

        return $thought;
    }

    /**
     * This function should only be run once after installing/updating the library.
     */
    public function insertDefaultThoughts(): void
    {
        if ($this->db instanceof SqlServer) {
            $sql = "IF OBJECT_ID(N'dbo.{$this->table}', N'U') IS NULL BEGIN
                CREATE TABLE {$this->table} (
                    thought_id int primary key identity,
                    thought nvarchar(500) not null,
                    author nvarchar(50) not null,
                    reference nvarchar(100) not null,
                    last_featured datetime2(0),
                    CONSTRAINT uq_thought UNIQUE (thought),
                    INDEX ix_last_featured (last_featured)
                );
                END";
        } else {
            // MySQL
            $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
                    thought_id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    thought varchar(500) not null,
                    author varchar(50) not null,
                    reference varchar(100) not null,
                    last_featured datetime,
                    CONSTRAINT uq_thought UNIQUE (thought),
                    INDEX ix_last_featured (last_featured)
                );";
        }

        $this->db->query($sql);

        /** @var list<array{thought: string}> $existing */
        $existing = $this->db->query("SELECT thought FROM {$this->table}")->getAll();
        $map = [];

        foreach ($existing as $row) {
            $map[$row['thought']] = true;
        }

        $utc = new DateTimeZone('UTC');
        $rows = [];

        foreach (self::getDefaultThoughts() as $thought) {
            if (isset($map[$thought->text])) {
                continue; // thought is already in the database
            }

            // insert with random last featured time so that featured thoughts will be shuffled
            $randomTimestamp = random_int(0, 86400); // random time on 1970-01-01
            $randomDatetime = new DateTimeImmutable("@{$randomTimestamp}", $utc);

            $rows[] = [
                'thought' => $thought->text,
                'author' => $thought->author,
                'reference' => $thought->reference,
                'last_featured' => $randomDatetime->format(self::DB_DATE),
            ];
        }

        $this->db->insertRows($this->table, $rows);
    }

    /**
     * @return list<Thought>
     */
    public static function getDefaultThoughts(): array
    {
        $json = file_get_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'dev_thoughts.json');
        $jsonArray = json_decode($json, true, flags: JSON_THROW_ON_ERROR);

        if (!isset($jsonArray['thoughts']) || !is_array($jsonArray['thoughts'])) {
            throw new \Exception("Unexpected JSON structure");
        }

        $thoughts = [];

        foreach ($jsonArray['thoughts'] as $key => $val) {
            if (!is_int($key) || !is_array($val)) {
                throw new \Exception("Unexpected structure for thought {$key}");
            }

            $thoughts[] = Thought::fromJsonArray($val);
        }

        return $thoughts;
    }
}
