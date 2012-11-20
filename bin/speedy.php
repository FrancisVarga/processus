#!/usr/bin/php -d memory_limit=536870912 -f

<?php

require __DIR__ . "/../vendor/autoload.php";

?>
\Application\ApplicationBootstrap::getInstance()->init("TASK");
\Processus\Task\Runner::run();
