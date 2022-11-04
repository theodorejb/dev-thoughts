<?php

declare(strict_types=1);

namespace theodorejb\DevThoughts;

use DateTimeImmutable;
use DateTimeZone;
use PeachySQL\PeachySql;

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
     * This function should only be run once, to avoid inserting duplicates.
     */
    public function insertDefaultThoughts(): void
    {
        $rows = [];

        foreach (self::getDefaultThoughts() as $thought) {
            $rows[] = [
                'thought' => $thought->text,
                'author' => $thought->author,
                'reference' => $thought->reference,
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
