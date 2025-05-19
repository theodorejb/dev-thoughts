<?php

namespace theodorejb\DevThoughts\Test;

use PHPUnit\Framework\TestCase;
use theodorejb\DevThoughts\DevThoughts;

final class DevThoughtsTest extends TestCase
{
    public function testGetDailyThought(): void
    {
        $thought = (new DevThoughts())->getDailyThought();
        $this->assertNotSame('', $thought->text);
    }

    public function testGetThought(): void
    {
        $thought = (new DevThoughts())->getThought(25);

        $this->assertEquals("Programming isn't about what you know; it's about what you can figure out.", $thought->text);
        $this->assertEquals('Chris Pine', $thought->author);
        $this->assertEquals('Learn to Program', $thought->reference);
    }
}
