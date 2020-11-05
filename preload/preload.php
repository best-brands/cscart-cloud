<?php

use Build\Preloader;

$classLoader = require "vendor/autoload.php";
$autoloader = new Preloader(dirname(__DIR__) . '/cscart');
$autoloader->setComposerLoader($classLoader);
$autoloader->autoLoadAll();
