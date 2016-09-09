<?php declare(strict_types = 1);

$loader = require __DIR__ . '/vendor/autoload.php';
$loader->add('Mikulas\\TranspilerBuild\\', __DIR__ . '/src');

$application = new \Mikulas\TranspilerBuild\Application();
$application->run();
