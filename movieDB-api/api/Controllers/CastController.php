<?php
/**
 * Author: Mason Noble
 * Date: 7/19/2020
 * File: CastController.php
 * Description:
 */

namespace movieDBAPI\Controllers;

use Psr\http\Message\ServerRequestInterface as Request;
use Psr\http\Message\ResponseInterface as Response;
use movieDBAPI\Models\Cast;

class CastController {
    //list all cast
    public function index(Request $request, Response $response, array $args) {
        $results = Cast::getCast($request);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

}