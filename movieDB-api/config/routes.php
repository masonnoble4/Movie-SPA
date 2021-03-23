<?php
/**
 * Author: Garrett Banning
 * Date: 7/12/2020
 * File: routes.php
 * Description: Define all of the routes for the API
 */

//use movieDBAPI\Authentication\MyAuthenticator;
//use movieDBAPI\Authentication\BasicAuthenticator;
//use movieDBAPI\Authentication\BearerAuthenticator;
//use movieDBAPI\Authentication\JWTAuthenticator;

use movieDBAPI\Authentication\ {
    MyAuthenticator,
    BasicAuthenticator,
    BearerAuthenticator,
    JWTAuthenticator
};

//Define app route
$app->get('/', function ($request, $response, $args) {
    return $response->write('<p><img src="../img/filmfinder.jpg" alt="logo" style="width:150px"></p>');
});

$app->get('/api/hello/{name}', function ($request, $response, $args) {
    return $response->write('Welcome to movieDB API ' . $args['name']);
});

//User routes
$app->group('/api/v1/users', function () {
    $this->get('', 'User:index');
    $this->get('/{id}', 'User:view');
    $this->post('', 'User:create');
    $this->put('/{id}', 'User:update');
    $this->delete('/{id}', 'User:delete');
    $this->post('/authBearer', 'User:authBearer');
    $this->post('/authJWT', 'User:authJWT');
});

//Route group
$app->group('/api/v1', function () {

    //The movie group
    $this->group('/movies', function () {
        $this->get('', 'Movie:index');
        $this->get('/{id}', 'Movie:view');
        $this->get('/{id}/genres', 'movieGenre:view');
        $this->post('', 'Movie:create');
        $this->put('/{id}', 'Movie:update');
        $this->delete('/{id}', 'Movie:delete');
    });

    //The person group
    $this->group('/persons', function () {
        $this->get('', 'Person:index');
        $this->get('/{id}', 'Person:view');
    });

    //The genre group
    $this->group('/genres', function () {
        $this->get('', 'Genre:index');
        $this->get('/{id}', 'Genre:view');
        $this->post('', 'Genre:create');
        $this->put('/{id}', 'Genre:update');
        $this->delete('/{id}', 'Genre:delete');
    });

    //the cast group
    $this->group('/cast', function () {
        $this->get('', 'Cast:index');
    });

    //the crew group
    $this->group('/crew', function () {
        $this->get('', 'Crew:index');
    });

    //the movie genre group
    $this->group('/movie-genres', function () {
        $this->get('', 'movieGenre:index');
        $this->get('/{id}', 'movieGenre:view');
    });
//})->add(new MyAuthenticator()); //MyAuthenticator - Custom header authentication
//})->add(new BasicAuthenticator()); //BasicAuthenticator - Basic authentication
//})->add(new BearerAuthenticator()); //Bearer Authenticator - Bearer authentication
})->add(new JWTAuthenticator()); //JWT Authenticator - JWT authentication