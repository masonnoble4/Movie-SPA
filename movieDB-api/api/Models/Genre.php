<?php
/**
 * Author: Kim Donlan
 * Date: 7/13/20
 * File: genre.php
 * Description: Genre class model
 */

namespace movieDBAPI\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model{
    //Table associated with genre model
    protected $table = 'genre';

    //Primary Key of table
    protected $primaryKey = 'genre_id';

    //PK is numeric
    public $incrementing = true;

    //Created_at and updated_at columns not used
    public $timestamps = false;

    public function movie_genre(){
        return $this->hasMany('movieDBAPI\Models\movieGenre','genre_id');
    }

    //Retrieve all genres
    public static function getGenres() {
        //$genres = self::all();
        //return $genres;
        $movie_g = self::with('movie_genre')->get();
        return $movie_g;
    }

    //Retrieve a specific genre by id
    public static function getGenreById(int $id) {
        $genre = self::findOrFail($id);
        return $genre;
    }

    //Search for genres
    public static function searchGenres($term){
        if(is_numeric($term)){
            $query = self::where('genre_id','>=',$term);
        }   else{
            $query = self::where('genre_name','like',"%$term%");
        }
        return $query->get();
    }

    //Create a new genre
    public static function createGenre($request) {
        $params = $request->getParsedBody();

        $genre = new Genre();

        foreach($params as $field => $value) {
            $genre->$field = $value;
        }

        $genre->save();
        return $genre;
    }

    //Update a genre
    public static function updateGenre($request) {
        $params = $request->getParsedBody();

        //Retrieve ID from request body
        $id = $request->getAttribute('id');
        $genre = self::find($id);
        if(!$genre) {
            return false;
        }

        //Update attributes of the genre
        foreach($params as $field=>$value) {
            $genre->$field = $value;
        }

        $genre->save();
        return $genre;
    }

    //Delete a genre
    public static function deleteGenre($request) {
        $id = $request->getAttribute('id');
        $genre = self::find($id);
        return($genre ? $genre->delete() : $genre);
    }
}