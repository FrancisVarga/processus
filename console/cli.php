<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hissterkiller
 * Date: 11/15/12
 * Time: 10:18 PM
 * To change this template use File | Settings | File Templates.
 */

require __DIR__ . "/../vendor/autoload.php";

$app = new \Symfony\Component\Console\Application();
$app->add(new \Processus\Cli\CreateNewApi());
$app->run();