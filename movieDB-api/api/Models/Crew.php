<?php
/**
 * Author: Mason Noble
 * Date: 7/19/2020
 * File: Crew.php
 * Description:
 */

namespace movieDBAPI\Models;

use Illuminate\Database\Eloquent\Model;

class Crew extends Model{
    //Table associated with movie_crew model
    protected $table = 'movie_crew';

    //Primary Key of table
    protected $primaryKey = 'movie_id';

    //PK is numeric
    public $incrementing = true;

    //Created_at and updated_at columns not used
    public $timestamps = false;

    public function movie() {
        return $this->belongsTo('movieDBAPI\Models\Movie', 'movie_id');
    }

    public function person() {
        return $this->belongsTo('movieDBAPI\Models\Person', 'person_id');
    }

    //Retrieve all cast
    public static function getCrew() {
        $crew = self::with(['movie', 'person'])->get();
        return $crew;
    }


}