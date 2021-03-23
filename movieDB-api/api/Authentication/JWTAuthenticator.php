<?php
/**
 * Author: Kim Donlan
 * Date: 7/27/20
 * File: JWTAuthenticator.php
 * Description: the JWT Authenticator class
 */

namespace movieDBAPI\Authentication;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use movieDBAPI\Models\User;

class JWTAuthenticator
{
    public function __invoke(Request $request, Response $response, $next)
    {
        //If the header named "movieDBAPI-Authorization does not exist then return an error.
        if (!$request->hasHeader('Authorization')) {
            $results = ['Status' => 'Authorization header not available'];
            return $response->withJson($results, 404, JSON_PRETTY_PRINT);
        }

        //Retrieve the header and the token
        $auth = $request->getHeader('Authorization');
        $token = substr($auth[0], strpos($auth[0], ' ') + 1);

        //validate the token
        if (!User::validateJWT($token)) {
            return $response->withJson(['Status' => 'Authentication failed.'], 401, JSON_PRETTY_PRINT);
        }

        // A user has been authenticated    `
        $response = $next($request, $response);
        return $response;
    }
}