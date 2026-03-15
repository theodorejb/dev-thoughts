<?php

declare(strict_types=1);

namespace theodorejb\DevThoughts;

/**
 * @phpstan-type ThoughtArr array{t: string, a?: string, r?: string}
 */
final class Thought
{
    public function __construct(
        public string $text,
        public string $author,
        public string $reference,
    ) {}

    /**
     * Return a single Thought from an array with the structure used in dev_thoughts.json
     * @param ThoughtArr $data
     */
    public static function fromJsonArray(array $data): self
    {
        $text = $data['t'];
        $author = $data['a'] ?? ''; // author is optional
        $reference = $data['r'] ?? ''; // reference is optional

        return new self($text, $author, $reference);
    }
}
