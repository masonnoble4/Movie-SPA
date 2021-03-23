<?php
/**
 * Author: Mason Noble
 * Date: 7/19/2020
 * File: movieGenre.php
 * Description:
 */

namespace movieDBAPI\Models;

use Illuminate\Database\Eloquent\Model;

class movieGenre extends Model
{
    //Table associated with movie model
    protected $table = 'movie_genres';

    //Primary Key of table
    protected $primaryKey = 'movie_id';

    //PK is numeric
    public $incrementing = true;

    //Created_at and updated_at columns not used
    public $timestamps = false;

    //Set up the one to many relation between movieGenre and Movie
    public function movie(){
        return $this->belongsTo('movieDBAPI\Models\Movie','movie_id');
    }

    //Set up the one to many relation between movieGenre and Genre
    public function genre() {
        return $this->belongsTo('movieDBAPI\Models\Genre', 'genre_id');
    }

    //Retrieve all movie Genres
    public static function getMovieGenres()
    {
        //$combined_mg = self::all();
        $combined_mg = self::with(['movie', 'genre'])->get();
        return $combined_mg;
    }

    //view a specific movie by genre
    public static function getMovieByGenre(int $id){
        $genres = self::findOrFail($id);
        $genres->load('movie')->load('genre');
        return $genres;

    }
}