<?php

declare(strict_types=1);

namespace theodorejb\DevThoughts;

use DateTimeImmutable;

/**
 * @psalm-import-type ThoughtRow from DevThoughts
 */
class Thought
{
    public function __construct(
        public int $id,
        public string $text,
        public string $author,
        public string $reference,
        public DateTimeImmutable|null $lastFeatured,
    ) {
    }

    /**
     * @param ThoughtRow $row
     */
    public static function fromDbRow(array $row): self
    {
        $lastFeatured = $row['last_featured'] ? new DateTimeImmutable($row['last_featured']) : null;
        return new self($row['thought_id'], $row['thought'], $row['author'], $row['reference'], $lastFeatured);
    }

    /**
     * Return a single Thought from an array with the structure used in dev_thoughts.json
     */
    public static function fromJsonArray(array $data): self
    {
        $t = $data['t'] ?? null; // thought text

        if (!is_string($t) || $t === '') {
            throw new \Exception("'t' property must be a non-empty string");
        }

        $a = $data['a'] ?? ''; // author (optional)

        if (!is_string($a)) {
            throw new \Exception("'a' property must be a string");
        }

        $r = $data['r'] ?? ''; // reference (optional)

        if (!is_string($r)) {
            throw new \Exception("'r' property must be a string");
        }

        return new self(0, $t, $a, $r, null);
    }
}
