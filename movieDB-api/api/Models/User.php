<?php
/**
 * Author: Garrett Banning
 * Date: 7/25/2020
 * File: User.php
 * Description: User Model Class
 */

namespace movieDBAPI\Models;

use Illuminate\Database\Eloquent\Model;
use Firebase\JWT\JWT;

class User extends Model
{
    //JWT secret
    const JWT_KEY = 'movieDBAPI-v1$';

    //the lifetime of the JWT token: seconds
    const JWT_EXPIRE = 3600;

    //Table associated with this model
    protected $table = 'users';

    //Primary key of the table
    protected $primaryKey = 'id';

    //The PK is non-numeric
    public $incrementing = true;

    //If the PK is not an integer, set its type
    protected $keyType = 'int';

    //If the created_at and updated_at columns are not used
    public $timestamps = true;

    //List all users
    public static function getUsers()
    {
        $users = self::all();
        return $users;
    }

    // View a specific user by id
    public static function getUserById(string $id)
    {
        $user = self::findOrFail($id);
        return $user;
    }

    // Create a new user
    public static function createUser($request)
    {
        // Retrieve parameters from request body
        $params = $request->getParsedBody();

        // Create a new User instance
        $user = new User();

        // Set the user's attributes
        foreach ($params as $field => $value) {

            // Need to hash password
            if ($field == 'password') {
                $value = password_hash($value, PASSWORD_DEFAULT);
            }

            // Skip role. It defaults to 2.
            if ($field == 'role') {
                continue;
            }

            $user->$field = $value;
        }

        // Insert the user into the database
        $user->save();
        return $user;
    }

    // Update a user
    public static function updateUser($request)
    {
        // Retrieve parameters from request body
        $params = $request->getParsedBody();

        //Retrieve the user's id from url and then the user from the database
        $id = $request->getAttribute('id');
        $user = self::findOrFail($id);

        // Update attributes of the user
        $user->name = $params['name'];
        $user->email = $params['email'];
        $user->username = $params['username'];
        $user->password = password_hash($params['password'], PASSWORD_DEFAULT);

        // Update the user
        $user->save();
        return $user;
    }

    // Delete a user
    public static function deleteUser($id)
    {
        $user = self::findOrFail($id);
        return ($user->delete());
    }

    /* ************* User Authentication and Authorization Methods ************************/

    //Authenticate user by username and password
    public static function authenticateUser($username, $password)
    {
        //Retrieve the records from the database table that matches the username
        $user = self::where('username', $username)->first();
        if (!$user) {
            return false;
        }

        //Verify password
        return password_verify($password, $user->password) ? $user : false;
    }

    /* ************* JWT Authentication ************************/
    /*
    * Generate a JWT token.
    * The signature secret rule: the secret must be at least 12 characters in length;
    * contain numbers; upper and lowercase letters; and one of the following special characters *&!@%^#$.
    * For more details, please visit https://github.com/RobDWaller/ReallySimpleJWT
    */
    public static function generateJWT($id)
    {
        //data for the payload
        $user = self::find($id);
        if (!$user) {
            return false;
        }

        $key = self::JWT_KEY;
        $expiration = time() + self::JWT_EXPIRE;
        $issuer = 'filmfinder.com';

        $token = [
            'iss' => $issuer,
            'exp' => $expiration,
            'isa' => time(),
            'data' => [
                'uid' => $id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ]
        ];

        //generate and return the token
        return JWT::encode(
            $token, //data to be encoded in the JWT
            $key, //the signing key
            'HS256' //algorithm used to sign the token; defaults to HS256
        );
    }

    //validate a JWT Token
    public static function validateJWT($token)
    {
        $decoded = JWT::decode($token, self::JWT_KEY, array('HS256'));
        return $decoded;
    }

}