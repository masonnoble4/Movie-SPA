<?php
/**
 * Author: Garrett Banning
 * Date: 7/12/2020
 * File: bootstrap.php
 * Description:
 */
//Load configuration settings
$config = require __DIR__ . '/config.php';

//Load composer autoload
require __DIR__ . '/../vendor/autoload.php';

//Prepare application
$app = new \Slim\App(['settings'=>$config]);

//Add dependencies
require __DIR__ . '/dependencies.php';

//Load the service factory
require __DIR__ . '/services.php';

//Include routes
require __DIR__ . '/routes.php';

