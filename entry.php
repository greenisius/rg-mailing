<?php

require_once __DIR__ . '/vendor/autoload.php';

use RG\Worker;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/.env');

Sentry\init(['dsn' => $_ENV['SENTRY_DSN'] ]);

$worker = new Worker();

$worker->work();