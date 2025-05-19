<?php

namespace theodorejb\DevThoughts;

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
