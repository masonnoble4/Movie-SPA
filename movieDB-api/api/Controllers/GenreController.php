<?php
/**
 * Author: Kim Donlan
 * Date: 7/13/20
 * File: GenreController.php
 * Description: Genre Controller Class
 */

namespace movieDBAPI\Controllers;

use movieDBAPI\Validation\Validator;
use Psr\http\Message\ServerRequestInterface as Request;
use Psr\http\Message\ResponseInterface as Response;
use movieDBAPI\Models\Genre;

class GenreController {
    //list all genres
    public function index(Request $request, Response $response, array $args) {
        //Get querystring variables from url
        $params = $request->getQueryParams();
        $term = array_key_exists('q',$params) ? $params['q'] : null;

        if(!is_null($term)){
            $results = Genre::searchGenres($term);
        }   else{
            $results = Genre::getGenres();
        }
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //view a specific genre
    public function view(Request $request, Response $response, array $args) {
        $id = $args['id'];
        $results = Genre::getGenreById($id);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //Create a genre
    public function create(Request $request, Response $response, array $args) {
        $validation = Validator::validateGenre($request);

        if(!$validation) {
            $results = [
                'status'=>"Validation failed",
                'errors'=>Validator::getErrors()
            ];

            return $response->withJson($results, 500, JSON_PRETTY_PRINT);
        }

    //Insert a new genre
        $genre = Genre::createGenre($request);
        $results = [
            'status'=>"Genre created",
            'data'=> $genre
        ];

        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //Update a genre
    public function update(Request $request, Response $response, array $args) {
        $validation = Validator::validateGenre($request);

        if(!$validation) {
            $results = [
                'status'=>"Validation failed",
                'errors'=>Validator::getErrors()
            ];

            return $response->withJson($results, 500, JSON_PRETTY_PRINT);
        }

        $genre = Genre::updateGenre($request);
        $status = $genre ? "Genre has been updated." : "Genre cannot be updated.";
        $status_code = $genre ? 200 : 500;
        $results['status'] = $status;
        if($genre) {
            $results['data'] = $genre;
        }
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }

    //Delete a genre
    public function delete(Request $request, Response $response, array $args) {
        $genre = Genre::deleteGenre($request);
        $status = $genre ? "Genre has been deleted." : "Genre cannot be deleted.";
        $status_code = $genre ? 200 : 500;
        $results = ['status' => $status];
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }
}