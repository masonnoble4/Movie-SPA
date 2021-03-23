<?php
/**
 * Author: Garrett Banning
 * Date: 7/12/2020
 * File: Movie.php
 * Description: Movie class model
 */

namespace movieDBAPI\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    //Table associated with movie model
    protected $table = 'movie';

    //Primary Key of table
    protected $primaryKey = 'movie_id';

    //PK is numeric
    public $incrementing = true;

    //Created_at and updated_at columns not used
    public $timestamps = false;

    //Set the One to Many relation between Movie and Movie Genres
    public function movieGenres() {
       return $this->hasMany('movieDBAPI\Models\movieGenre', 'movie_id');
    }

    //Retrieve all movies
    public static function getMovies()
    {
        $movies = self::with('movieGenres')->get();
        return $movies;
    }

    //Retrieve a specific movie by id
    public static function getMovieById(int $id)
    {
        $movie = self::findOrFail($id);
        $movie->load('movieGenres');
        return $movie;
    }

    //View all genres associate to a movie
    public static function getGenresByMovie(int $id) {
        $genres = self::findOrFail($id)->movieGenres;
        return $genres;
    }

    //insert a new movie
    public static function createMovie($request) {
        //retrieve parameters from request body
        $params = $request->getParsedBody();

        //create a new movie instance
        $movie = new Movie();

        //set a movie's attributes
        foreach($params as $field => $value) {
            $movie->$field = $value;
        }

        //insert the movie into the database
        $movie->save();
        return $movie;
    }

    //Update a movie
    public static function updateMovie($request) {
        //retrieve parameters from request body
        $params = $request->getParsedBody();

        //retrieve id from the request body
        $id = $request->getAttribute('id');
        $movie = self::find($id);
        if (!$movie) {
            return false;
        }

        //update attributes of the movie
        foreach($params as $field => $value) {
            $movie->$field = $value;
        }

        //save the movie into the database
        $movie->save();
        return $movie;
    }

    //delete a movie
    public static function deleteMovie($request)
    {
        //retrieve the id from the request
        $id = $request->getAttribute('id');
        $movie = self::find($id);
        return ($movie ? $movie->delete() : $movie);
    }

    //Search for movies
    public static function searchMovies($term){
        if(is_numeric($term)){
            $query = self::where('popularity','>=',$term);
        }   else{
            $query = self::where('title','like',"%$term%")
                -> orWhere('tagline','like',"%$term%")
                -> orWhere('vote_average','like',"%$term%")
                -> orWhere('runtime','like',"%$term%");
        }
        return $query->get();
    }
}