<?php
/**
 * Author: Mason Noble
 * Date: 7/13/2020
 * File: Person.php
 * Description: Person Class Model
 */
namespace movieDBAPI\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model{
    //Table associated with movie model
    protected $table = 'person';

    //Primary Key of table
    protected $primaryKey = 'person_id';

    //PK is numeric
    public $incrementing = true;

    //Created_at and updated_at columns not used
    public $timestamps = false;

    //Relations, may need to change method names to work with your code
    public function cast(){
        return $this->hasMany('movieDBAPI\Models\Cast','person_id');
    }
    public function crew(){
        return $this->hasMany('movieDBAPI\Models\Crew','person_id');
    }

    //Retrieve all movies with pagination and sorting
    public static function getPersons($request) {
        //Get the total number of row count
        $count = self::count();

        //Get querystring variables from url
        $params = $request->getQueryParams();

        //Check if Limit and Offset exist
        $limit = array_key_exists('limit', $params) ? (int)$params['limit'] : 10; //items per page
        $offset = array_key_exists('offset', $params) ? (int)$params['offset'] : 0; //offset

        //pagination
        $links = self::getLinks($request, $limit, $offset);

        //sorting
        $sort_key_array = self::getSortKeys($request);

        //Build query
        $query = self::with('cast', 'crew'); //Builds query with relationships
        $query = $query->skip($offset)->take($limit); //limit the rows

        //Sort output
        foreach($sort_key_array as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        //Run query and get results
        $persons = $query->get();

        //Construct data for response
        $results = [
            'totalCount' => $count,
            'limit' => $limit,
            'offset' => $offset,
            'links' => $links,
            'sort' => $sort_key_array,
            'data' => $persons
        ];

        return $results;
    }

    //Retrieve a specific person by id
    public static function getPersonById(int $id) {
        $person = self::findOrFail($id);
        return $person;
    }

    // This function returns an array of links for pagination. The array includes links for the current, first, next, and last pages.
    private static function getLinks($request, $limit, $offset) {
        $count = self::count();

        // Get request uri and parts
        $uri = $request->getUri();
        $base_url = $uri->getBaseUrl();
        $path = $uri->getPath();

        // Construct links for pagination
        $links = array();
        $links[] = ['rel' => 'self', 'href' => $base_url . "/" . $path . "?limit=$limit&offset=$offset"];
        $links[] = ['rel' => 'first', 'href' => $base_url . "/" . $path . "?limit=$limit&offset=0"];
        if ($offset - $limit >= 0) {
            $links[] = ['rel' => 'prev', 'href' => $base_url . "/" . $path . "?limit=$limit&offset=" . ($offset - $limit)];
        }
        if ($offset + $limit < $count) {
            $links[] = ['rel' => 'next', 'href' => $base_url . "/" . $path . "?limit=$limit&offset=" . ($offset + $limit)];
        }
        $links[] = ['rel' => 'last', 'href' => $base_url . "/" . $path . "?limit=$limit&offset=" . $limit * (ceil($count / $limit) - 1)];

        return $links;
    }

    /*
     * Sort keys are optionally enclosed in [ ], separated with commas;
     * Sort directions can be optionally appended to each sort key, separated by :.
     * Sort directions can be 'asc' or 'desc' and defaults to 'asc'.
     * Examples: sort=[number:asc,title:desc], sort=[number, title:desc]
     * This function retrieves sorting keys from uri and returns an array.
    */
    private static function getSortKeys($request) {
        $sort_key_array = array();

        // Get querystring variables from url
        $params = $request->getQueryParams();

        if (array_key_exists('sort', $params)) {
            $sort = preg_replace('/^\[|\]$|\s+/', '', $params['sort']);  // remove white spaces, [, and ]
            $sort_keys = explode(',', $sort); //get all the key:direction pairs
            foreach ($sort_keys as $sort_key) {
                $direction = 'asc';
                $column = $sort_key;
                if (strpos($sort_key, ':')) {
                    list($column, $direction) = explode(':', $sort_key);
                }
                $sort_key_array[$column] = $direction;
            }
        }

        return $sort_key_array;
    }
}