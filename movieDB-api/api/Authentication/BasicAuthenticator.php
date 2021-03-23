<?php
/**
 * Author: Garrett Banning
 * Date: 7/27/2020
 * File: BasicAuthenticator.php
 * Description:
 */
namespace movieDBAPI\Authentication;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use movieDBAPI\Models\User;

class BasicAuthenticator {
    public function __invoke(Request $request, Response $response, $next) {
        /*
         * Username and password are sent in header called "Authorization" in the format
         * Basic username:password. Username and password are encoded.
         */
        if (!$request->hasHeader('Authorization')) {
            $results = array('status' => 'Authorization header not available');
            return $response->withJson($results, 404, JSON_PRETTY_PRINT);
        }

        //Retrieve header with username:password
        $auth = $request->getHeader('Authorization');

        $apikey = substr($auth[0], strpos($auth[0], ' ') + 1); // the key is the second part of the string after a space

        //Decode, get username and password
        list($user, $password) = explode(':', base64_decode($apikey));

        //Authenticate the user
        if(!User::authenticateUser($user, $password)) {
            $results = array('status' => 'Authentication failed');
            return $response->withHeader('WWW-Authenticate',  'Basic realm="movieDBAPI"')->withJson($results, 401, JSON_PRETTY_PRINT);
        }

        $response = $next($request, $response);
        return $response;
    }
}