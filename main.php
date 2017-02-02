<?php

require_once __DIR__ . '/vendor/autoload.php';

use Src\Checking;
use Src\Chatwork;

if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}

$checking = new Checking();
$result = $checking->exec();

$chatwork = new Chatwork();
$message = $chatwork->createMessage($result);
$chatwork->sendMessage($message);