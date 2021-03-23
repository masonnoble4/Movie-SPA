<?php
/**
 * Author: Garrett Banning
 * Date: 7/12/2020
 * File: services.php
 * Description: Create the Service Factory by registering all service within the container
 */
use movieDBAPI\Controllers\MovieController;
use movieDBAPI\Controllers\PersonController;
use movieDBAPI\Controllers\GenreController;
use movieDBAPI\Controllers\CastController;
use movieDBAPI\Controllers\CrewController;
use movieDBAPI\Controllers\movieGenreController;
use movieDBAPI\Controllers\UserController;

//Register Controller with the DIC
$container['Movie'] = function($c) {
    return new MovieController();
};

//Register Person Controller with the DIC
$container['Person'] = function($c) {
    return new PersonController();
};

//Register Genre Controller with the DIC
$container['Genre'] = function($c) {
    return new GenreController();
};

//Register Cast Controller with the DIC
$container['Cast'] = function($c) {
    return new CastController();
};

//Register Crew Controller with the DIC
$container['Crew'] = function($c) {
    return new CrewController();
};

//Register movie Genre Controller with the DIC
$container['movieGenre'] = function($c) {
    return new movieGenreController();
};

//Register User Controller with the DIC
$container['User'] = function($c) {
    return new UserController();
};