<?php
/**
 * Author: Kim Donlan
 * Date: 7/19/20
 * File: Validator.php
 * Description: Defines methods that validate data of models
 */

namespace movieDBAPI\Validation;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator
{
    private static $errors = [];

    //Generic validation method. It returns true on success or false on failure
    public static function validate($request, array $rules)
    {
        foreach ($rules as $field => $rule) {
            //Retrieve parameters from the URL or the request body
            $param = $request->getAttribute($field) ?? $request->getParam($field);
            try {
                $rule->setName(ucfirst($field))->assert($param);
            } catch (NestedValidationException $ex) {
                self::$errors[$field] = $ex->getMessage();
            }
        }
        return empty(self::$errors);
    }

    //Validate attributes of a Genre object
    public static function validateGenre($request) {
        $rules = [
            'genre_id' => v::number(),
            'genre_name' => v::alpha(' ', ':', '.')
        ];

        return self::validate($request, $rules);
    }

    //validate attributes of a Movie object
    public static function validateMovie($request)
    {
        //define the validation rules - commented out to test functionality.
        $rules = [
            'movie_id' => v::number(),
            'title' => v::alnum(' ', ':', ',', '.', '-'),
            'budget' => v::number(),
            'homepage' => v::url(),
            'overview' => v::stringVal(),
            'popularity' => v::alnum('.'),
            'release_date' => v::date(),
            'revenue' => v::number(),
            'runtime' => v::number(),
            'movie_status' => v::alpha(' '),
            'tagline' => v::stringVal(),
            'vote_average' => v::alnum('.', ''),
            'vote_count' => v::number()
        ];

        return self::validate($request, $rules);
    }

    //Validate new user
    public function validateUser($request) {
        $rules = [
            'name' => v::alnum(' '),
            'email' => v::email(),
            'username' => v::notEmpty(),
            'password' => v::notEmpty()
        ];

        return self::validate($request, $rules);
    }

    //return the errors in an array
    public static function getErrors()
    {
        return self::$errors;
    }
}