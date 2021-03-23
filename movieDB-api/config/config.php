<?php
/**
 * Author: Garrett Banning
 * Date: 7/12/2020
 * File: config.php
 * Description: configuration file
 */

return [
    //display error details in the development environment
    'displayErrorDetails' => true,

    //Database connection details
    'db' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'movies',
        'username' => 'phpuser',
        'password' => 'phpuser',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => ''
    ]
];