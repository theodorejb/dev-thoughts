<?php

declare(strict_types=1);

namespace theodorejb\DevThoughts;

/**
 * @phpstan-import-type ThoughtArr from Thought
 */
final class DevThoughts
{
    public function getDailyThought(): Thought
    {
        $day = (int) date('z');
        return $this->getThought($day);
    }

    public function getThought(int $index): Thought
    {
        $thoughts = $this->getAllThoughts();
        $wrappedIndex = $index % count($thoughts);

        return $thoughts[$wrappedIndex];
    }

    /**
     * @return list<Thought>
     */
    public function getAllThoughts(): array
    {
        $json = file_get_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'dev_thoughts.json');

        if ($json === false) {
            throw new \Exception('Failed to load dev_thoughts.json');
        }

        /** @var array{thoughts: ThoughtArr[]} $jsonArray */
        $jsonArray = json_decode($json, true, flags: JSON_THROW_ON_ERROR);
        $thoughts = [];

        foreach ($jsonArray['thoughts'] as $val) {
            $thoughts[] = Thought::fromJsonArray($val);
        }

        return $thoughts;
    }
}
