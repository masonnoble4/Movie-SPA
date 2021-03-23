<?php
/**
 * Author: Mason Noble
 * Date: 7/13/2020
 * File: PersonController.php
 * Description: Person Controller
 */

namespace movieDBAPI\Controllers;

use Psr\http\Message\ServerRequestInterface as Request;
use Psr\http\Message\ResponseInterface as Response;
use movieDBAPI\Models\Person;

class PersonController {
    //list all persons with pagination
    public function index(Request $request, Response $response, array $args) {
        $results = Person::getPersons($request);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //view a specific person
    public function view(Request $request, Response $response, array $args) {
        $id = $args['id'];
        $results = Person::getPersonById($id);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }
}