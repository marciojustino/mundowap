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
    header_remove();
    http_response_code($code);
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type");
    header('Content-Type: application/json');
    header('Status: ' . $code);

    return json_encode(array(
        'status' => $code,
        'message' => $message,
        'data' => $data
    ));
}