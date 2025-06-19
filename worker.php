<?php

declare(strict_types=1);

use Spiral\RoadRunnerBridge\Kernel;
use Spiral\RoadRunnerBridge\Worker;

require __DIR__ . '/vendor/autoload.php';

$kernel = new Kernel();
$kernel->serve(Worker::create());
