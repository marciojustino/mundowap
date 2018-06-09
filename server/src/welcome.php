<?php
/**
 * Created by PhpStorm.
 * User: marciojustino
 */

require_once './../vendor/autoload.php';
require_once './database.php';

$db = open_database();

session_start();

$user = $_SESSION['user'];
if (!$user) {
    header('location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = $_POST['import'];
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
        $colIndex = 1;

        foreach ($results as $sheet) {
            foreach ($sheet as $cell) {
                if ($rowIndex == 1) {
                    switch ($colIndex) {
                        case 1:
                            if (!$cell != 'EAN') {
                                // error
                            }
                            break;
                        case 2:
                            if ($cell != 'NOME PRODUTO') {
                                // error
                            }
                            break;
                        case 3:
                            if ($cell != 'PREÇO') {
                                // error
                            }
                            break;
                        case 4:
                            if ($colIndex == 4 && $cell != 'ESTOQUE') {
                                // error
                            }
                            break;
                        case 5:
                            if ($colIndex == 5 && $cell != 'DATA FABRICAÇÃO') {
                                // error
                            }
                            break;
                    }

                    if ($colIndex > 5)
                        break;

                } else {

                    // get file content
                    switch ($colIndex) {
                        case 1:
                            $ean = $cell;
                            break;
                        case 2:
                            $name = $cell;
                            break;
                        case 3:
                            $price = $cell;
                            break;
                        case 4:
                            $qtd = $cell;
                            break;
                        case 5:
                            $fabricated_at = $cell;
                            break;
                    }

                    if ($colIndex > 5)
                        break;

                }
                $colIndex++;
            }

            $rowIndex++;
            $colIndex = 0;

            echo $rowIndex - 1 . ' - ' . $ean;
            echo '<br>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css"
          integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
            integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"
            integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T"
            crossorigin="anonymous"></script>
</head>
<body>
<div class="container-fluid">
    <h2>Produtos</h2>
    <div class="jumbotron">
        <form action="" method="post" name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
            <div class="form-group">
                <label>Importar arquivo</label>
                <input type="file" name="file" id="file" accept=".xls,.xlsx">
            </div>
            <button type="submit" id="submit" name="import" class="btn btn-primary">Importar</button>
        </form>
    </div>
    <div id="response" class="<?php if (!empty($type)) {
        echo $type . " display-block";
    } ?>"><?php if (!empty($message)) {
            echo $message;
        } ?></div>

    <div>
        <?php
        $sql = "SELECT id, ean, name, price, qtd, fabricated_at FROM products";
        if ($result = $db->query($sql)) {
            echo '<table class="table table-bordered">';
            echo '<thead class="thead-dark">';
            echo '<tr>';
            echo '<th>#</th>';
            echo '<th>EAN</th>';
            echo '<th>NOME PRODUTO</th>';
            echo '<th>PREÇO</th>';
            echo '<th>ESTOQUE</th>';
            echo '<th>DATA FABRICAÇÃO</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            while ($cell = $result->fetch_array()) {
                echo '<tr>';
                echo '<td><a href="">Excluir</a></td>';
                echo '<td>' . $cell["ean"] . '</td>';
                echo '<td>' . $cell["name"] . '</td>';
                echo '<td>' . $cell["price"] . '</td>';
                echo '<td>' . $cell["qtd"] . '</td>';
                echo '<td>' . $cell["fabricated_at"] . '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        }
        ?>
    </div>
</div>
</body>
</html>