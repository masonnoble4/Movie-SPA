<?php
/**
 * Author: Mason Noble
 * Date: 7/19/2020
 * File: movieGenreController.php
 * Description:
 */

namespace movieDBAPI\Controllers;

use Psr\http\Message\ServerRequestInterface as Request;
use Psr\http\Message\ResponseInterface as Response;
use movieDBAPI\Models\movieGenre;

class movieGenreController
{
    //list all movieGenres
    public function index(Request $request, Response $response, array $args)
    {
        $results = movieGenre::getMovieGenres($request);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //view a specific movieGenre
    public function view(Request $request, Response $response, array $args){
        $results = movieGenre::getMovieByGenre($args['id']);
        return $response->withJson($results, 200,JSON_PRETTY_PRINT);
    }
}