<?php

namespace theodorejb\DevThoughts;

final class Thought
{
    public function __construct(
        public string $text,
        public string $author,
        public string $reference,
    ) {}

    /**
     * Return a single Thought from an array with the structure used in dev_thoughts.json
     */
    public static function fromJsonArray(array $data): self
    {
        $text = $data['t'] ?? null;

        if (!is_string($text) || $text === '') {
            throw new \Exception("'t' property must be a non-empty string");
        }

        $author = $data['a'] ?? ''; // author is optional

        if (!is_string($author)) {
            throw new \Exception("'a' property must be a string");
        }

        $reference = $data['r'] ?? ''; // reference is optional

        if (!is_string($reference)) {
            throw new \Exception("'r' property must be a string");
        }

        return new self($text, $author, $reference);
    }
}
