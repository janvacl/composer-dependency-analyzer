#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use S3tezsky\DependencyAnalyzer\AppKernel;
use Symfony\Component\Console\Application;

$kernel = new AppKernel(AppKernel::ENVIRONMENT_TEST, false);
$kernel->boot();

$container = $kernel->getContainer();
$application = $container->get(Application::class);
$application->run();
