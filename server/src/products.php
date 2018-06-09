<?php
/**
 * Created by PhpStorm.
 * User: marciojustino
 */

require_once './../vendor/autoload.php';
require_once './database.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

$db = open_database();
if (!$db) {
    echo json_response('Banco de dados não conectado!', 500);
}

try {
    $sql = "SELECT id, ean, name, price, qtd, fabricated_at FROM products";
    $result = $db->query($sql);
    $response = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_response(null, 200, $response);
} catch (Exception $e) {
    echo json_response($e->getMessage(), 500);
} finally {
    close_database($db);
}