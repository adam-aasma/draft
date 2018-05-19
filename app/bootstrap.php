<?php

require __DIR__ . '/../vendor/autoload.php';

// Basic config for Slim Application
$config = array(
   'settings' => [
        'displayErrorDetails' => true,

        'logger' => [
            'name' => 'slim-app',
            'level' => Monolog\Logger::DEBUG,
            'path' => __DIR__ . '/../share/logs/walltwisters.log',
        ],
    ],
);

// Load config file
$configFile = dirname(__FILE__) . '/../share/config/default.php';

if (is_readable($configFile)) {
    require_once $configFile;
}

// Create application instance with config
$app = new Slim\App($config);


//DIC set up
$container = $app->getContainer();

$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('walltwister_logger');
    $file_handler = new \Monolog\Handler\StreamHandler('../share/logs/walltwister.log');
    $logger->pushHandler($file_handler);
    return $logger;
};

$container['view'] = new \Slim\Views\PhpRenderer('../views/');