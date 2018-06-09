<?php
/**
 * Created by PhpStorm.
 * User: marciojustino
 */

require_once './../vendor/autoload.php';
require_once './database.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");

$db = open_database();
if (!$db) {
    echo json_response('Banco de dados não conectado!', 500);
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        $sql = "SELECT id, ean, name, price, qtd, fabricated_at FROM products";
        $result = $db->query($sql);
        $response = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo json_response(null, 200, $response);

    } else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        $productId = $_REQUEST["id"];
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('d', $productId);
        $stmt->execute();
        echo json_response('Produto removido com sucesso.', 200);

    }
} catch (Exception $e) {
    echo json_response($e->getMessage(), 500);
} finally {
    close_database($db);
}