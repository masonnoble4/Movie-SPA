<?php
/**
 * Author: Mason Noble
 * Date: 7/19/2020
 * File: Cast.php
 * Description:
 */

namespace movieDBAPI\Models;

use Illuminate\Database\Eloquent\Model;

class Cast extends Model
{
    //Table associated with movie model
    protected $table = 'movie_cast';

    //Primary Key of table
    protected $primaryKey = 'id';

    //PK is numeric
    public $incrementing = true;

    //Created_at and updated_at columns not used
    public $timestamps = false;

    public function movie()
    {
        return $this->belongsTo('movieDBAPI\Models\Movie', 'movie_id');
    }

    public function person() {
        return $this->belongsTo('movieDBAPI\Models\Person', 'person_id');
    }

    //Retrieve all cast
    public static function getCast()
    {
        //$cast = self::all();
        //return $cast;
        $combined_mg = self::with(['movie', 'person'])->get();
        return $combined_mg;
    }


}