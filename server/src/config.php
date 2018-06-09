<?php
/**
 * Created by PhpStorm.
 * User: marciojustino
 */

define('DB_HOST', 'database');
define('DB_NAME', 'mundowaptest');
define('DB_USER', 'root');
define('DB_PASSWORD', 'secret');

function json_response($message = null, $code = 200, $data = null)
{
    // clear the old headers
    header_remove();
    // set the actual code
    http_response_code($code);
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type");
    // set the header to make sure cache is forced
    header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
    // treat this as json
    header('Content-Type: application/json');

    // ok, validation error, or failure
    header('Status: ' . $code);
    // return the encoded json
    return json_encode(array(
        'status' => $code,
        'message' => $message,
        'data' => $data
    ));
}