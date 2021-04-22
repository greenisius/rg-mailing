<?php

require_once __DIR__ . '/vendor/autoload.php';

use RG\Worker;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/.env');

$worker = new Worker();

$worker->work();