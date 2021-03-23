<?php
/**
 * Author: Garrett Banning
 * Date: 7/25/2020
 * File: MyAuthenticator.php
 * Description: The MyAuthenticator class authenticates user with username and password in header.
 */
namespace movieDBAPI\Authentication;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use movieDBAPI\Models\User;

class MyAuthenticator {
    /*
     * Use the __invoke method so the object can be used as a callable.
     * This method gets called automatically when the object is treated as a callable.
     */
    public function __invoke(Request $request, Response $response, $next) {
        //Username and password are stored in a header called "movieDBAPI-Authorization"
        //Value of header is formatted as username:password
        if(!$request->hasHeader('movieDBAPI-Authorization')){
            $results = ['Status' => 'Authorization header not found.'];
            return $response->withJson($results, 404, JSON_PRETTY_PRINT);
        }

        //Retrieve the header, username, and password
        $auth = $request->getHeader('movieDBAPI-Authorization');
        list($username, $password) = explode(':', $auth[0]);

        //Validate username and password
        if(!User::authenticateUser($username, $password)) {
            $results = ['Status' => 'Authentication failed.'];
            return $response->withJson($results, 404, JSON_PRETTY_PRINT);
        }

        //A user has been authenticated
        $response = $next($request, $response);
        return $response;
    }
}