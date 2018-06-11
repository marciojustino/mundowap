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

//Receive the RAW post data.
$content = file_get_contents("php://input");

//Attempt to decode the incoming RAW post data from JSON.
$post = json_decode($content, true);

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username is empty
    if (empty($post["username"])) {
        echo json_response('Informe o nome do usuário', 404);
        return;
    } else {
        $username = trim($post["username"]);
    }

    // Check if password is empty
    if (empty(trim($post['password']))) {
        echo json_response('Informe a senha do usuário.', 404);
        return;
    } else {
        $password = trim($post['password']);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT username, password FROM users WHERE username = ? and password = ?";

        if ($stmt = $db->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ss", $username, md5($password));

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                // Check if username exists, if yes then verify password
                if ($stmt->num_rows == 1) {
                    echo json_response('success', 200, ['user' => $username]);
                } else {
                    echo json_response('No account found with that username.', 404);
                }
            } else {
                echo json_response('Oops! Something went wrong. Please try again later.', 400);
            }
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    close_database($db);
}