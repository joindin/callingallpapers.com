#!/usr/bin/env php
<?php
// callingallpapers.php

require __DIR__.'/../vendor/autoload.php';

use Callingallpapers\Console\Command\CreateAtomCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new CreateAtomCommand());
$application->run();