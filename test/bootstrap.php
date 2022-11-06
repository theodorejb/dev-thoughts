<?php

declare(strict_types=1);

use theodorejb\DevThoughts\Test\{DbConnector, Config, LocalConfig};

require 'vendor/autoload.php';

if (class_exists(LocalConfig::class)) {
    // suppress error when LocalConfig doesn't exist
    /** @psalm-suppress MixedArgument */
    DbConnector::setConfig(new LocalConfig());
} else {
    DbConnector::setConfig(new Config());
}
