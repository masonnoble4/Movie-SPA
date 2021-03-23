<?php
/**
 * Author: Garrett Banning
 * Date: 7/12/2020
 * File: MovieController.php
 * Description: Movie controller class
 */

namespace movieDBAPI\Controllers;

use movieDBAPI\Validation\Validator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use movieDBAPI\Models\Movie;

class MovieController {
    //list all movies
    public function index(Request $request, Response $response, array $args)
    {
        //Get querystring variables from url
        $params = $request->getQueryParams();
        $term = array_key_exists('q',$params) ? $params['q'] : null;

        if(!is_null($term)){
            $results = Movie::searchMovies($term);
        }   else{
            $results = Movie::getMovies();
        }

        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //view a specific movie
    public function view(Request $request, Response $response, array $args)
    {
        $id = $args['id'];
        $results = Movie::getMovieById($id);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //View all genres associated to a movie
    public function viewGenres(Request $request, Response $response, array $args) {
        $id = $args['id'];
        $results = Movie::getGenresByMovie($id);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //create a movie
    public function create(Request $request, Response $response, array $args) {
        //validate the request
        $validation = Validator::validateMovie($request);

        //if validation fails
        if (!$validation) {
            $results = [
                'status' => "Validation failed",
                'errors' => Validator::getErrors()
            ];

            return $response->withJson($results, 500, JSON_PRETTY_PRINT);
        }


        //insert a new movie
        $movie = Movie::createMovie($request);
        $results = [
            'status' => "Movie created",
            'data' => $movie
        ];
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //update a movie
    public function update(Request $request, Response $response, array $args)
    {
        // Validate the request
        $validation = Validator::validateMovie($request);

        //if validation fails
        if (!$validation) {
            $results['status'] = "Validation failed";
            $results['errors'] = Validator::getErrors();
            return $response->withJson($results, 500, JSON_PRETTY_PRINT);
        }

        $movie = Movie::updateMovie($request);
        $status = $movie ? "Movie has been updated." : "Movie cannot be updated.";
        $status_code = $movie ? 200 : 500;
        $results['status'] = $status;
        if ($movie) {
            $results['data'] = $movie;
        }
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }

    //delete a movie
    public function delete(Request $request, Response $response, array $args)
    {
        $movie = Movie::deleteMovie($request);
        $status = $movie ? "Movie has been deleted." : "Movie cannot be deleted.";
        $status_code = $movie ? 200 : 500;
        $results = ['status' => $status];
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }
}
