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

$targetPath = "";

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $file = $_FILES["file"];

        if (isset($file)) {
            PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
            $targetPath = __DIR__ . '/' . $_FILES['file']['name'];
            move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
            $excelReader = PHPExcel_IOFactory::createReaderForFile($targetPath);
            $excelReader->setReadDataOnly();
            $excelReader->setLoadAllSheets();
            $excelObj = $excelReader->load($targetPath);
            $results = $excelObj->getActiveSheet()->toArray(null, true, true, true);

            $rowIndex = 1;
            $colIndex = 0;

            $db->begin_transaction();

            foreach ($results as $sheet) {
                foreach ($sheet as $cell) {

                    if ($cell == NULL)
                        continue;

                    if ($rowIndex == 1) {
                        switch ($colIndex) {
                            case 0:
                                if ($colIndex == 0 && $cell != 'EAN') {
                                    echo json_response('Formato do arquivo é inválido! Primeiro campo deve ser EAN' . $cell, 404);
                                    exit();
                                }
                                break;
                            case 1:
                                if ($colIndex == 1 && $cell != 'NOME PRODUTO') {
                                    echo json_response('Formato do arquivo é inválido! Segundo campo deve ser NOME PRODUTO' . $cell, 404);
                                    exit();
                                }
                                break;
                            case 2:
                                if ($colIndex == 2 && $cell != 'PREÇO') {
                                    echo json_response('Formato do arquivo é inválido! Terceiro campo deve ser PREÇO' . $cell, 404);
                                    exit();
                                }
                                break;
                            case 3:
                                if ($colIndex == 3 && $cell != 'ESTOQUE') {
                                    echo json_response('Formato do arquivo é inválido! Quarto campo deve ser ESTOQUE' . $cell, 404);
                                    exit();
                                }
                                break;
                            case 4:
                                if ($colIndex == 4 && $cell != 'DATA FABRICAÇÃO') {
                                    echo json_response('Formato do arquivo é inválido! Quinto campo deve ser DATA FABRICAÇÃO' . $cell, 404);
                                    exit();
                                }
                                break;
                        }

                        if ($colIndex > 4)
                            break;

                    } else {

                        // get file content
                        switch ($colIndex) {
                            case 0:
                                $ean = $cell;
                                if (!isset($ean)) {
                                    echo json_response('Campo EAN é obrigatório para todos os registros. EAN: ' . $ean, 404);
                                    return;
                                }
                                break;
                            case 1:
                                $name = $cell;
                                if (!isset($name)) {
                                    echo json_response('Campo NOME PRODUTO é obrigatório para todos os registros. NOME PRODUTO: ' . $name, 404);
                                    return;
                                }
                                break;
                            case 2:
                                $price = $cell;
                                if (!isset($price)) {
                                    echo json_response('Campo PREÇO é obrigatório para todos os registros. PREÇO: ' . $price, 404);
                                    return;
                                }
                                break;
                            case 3:
                                $qtd = $cell;
                                if (!isset($qtd)) {
                                    echo json_response('Campo ESTOQUE é obrigatório para todos os registros. ESTOQUE: ' . $qtd, 404);
                                    return;
                                }
                                break;
                            case 4:
                                $fabricated_at = $cell;
                                break;
                        }

                        if ($colIndex > 4)
                            break;

                    }
                    $colIndex++;
                }

                $rowIndex++;
                $colIndex = 0;

                if (empty($ean))
                    continue;

                //search for register in database
                $sql = "Select ean from products where ean = ?";

                if ($stmt = $db->prepare($sql)) {
                    $stmt->bind_param("s", $ean);
                    if ($stmt->execute()) {
                        $stmt->store_result();
                        $stmt->bind_result($product);

                        if (empty($product)) {
                            // add new product
                            $price = str_replace("R$", "", $price);
                            if (!empty($fabricated_at)) {
                                $date = date_create_from_format("d/m/Y", $fabricated_at);
                                $date = $date->format("Y-m-d");
                                $sql = "INSERT INTO products (ean, name, price, qtd, fabricated_at) VALUES ('$ean','$name',$price,$qtd,'$date')";
                            } else {
                                $sql = "INSERT INTO products (ean, name, price, qtd) VALUES ('$ean','$name',$price,$qtd)";
                            }

                            $db->query($sql);
//                            if($db->error) {
//                                echo json_response($db->error, 404);
//                                return;
//                            }
                        }
                    }
                }
            }

            $db->commit();
            echo json_response('Arquivo importado com sucesso!', 200);
        }
    } else {
        echo json_response('Método não permitido.', 405);
    }
} catch (Exception $e) {
    $db->rollback();
    echo json_response($e->getMessage(), 500);
} finally {
    close_database($db);
    // delete temporary file
    if (file_exists($targetPath)) {
        unlink($targetPath);
    }
}