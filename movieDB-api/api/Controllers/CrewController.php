<?php
/**
 * Author: Mason Noble
 * Date: 7/19/2020
 * File: CrewController.php
 * Description:
 */

namespace movieDBAPI\Controllers;

use Psr\http\Message\ServerRequestInterface as Request;
use Psr\http\Message\ResponseInterface as Response;
use movieDBAPI\Models\Crew;

class CrewController {
    //list all crew
    public function index(Request $request, Response $response, array $args) {
        $results = Crew::getCrew($request);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }
}