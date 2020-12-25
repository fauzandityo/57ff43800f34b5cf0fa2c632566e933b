<?php

    // Headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    // Instantiate tools
    include_once "../config/database.php";
    include_once "../utility/helper.php";

    // Instantiate object
    include_once "../objects/user.php";

    $database = new Database();
    $db = $database->getConnection();

    // Initialize object needed
    $user = new User($db);
    $helper = new Helper();

    // Retrieve data
    $data = json_decode(file_get_contents("php://input"));

    // Check if data empty
    if (!empty($data->username) &&
        !empty($data->password)) {
        // Check format username given
        if ($helper->email_regex($data->username)) {
            // Hash password
            $helper->pass = $data->password;
            $helper->hash_pass();

            // Set values to properties
            $user->username = $data->username;
            $user->password = $helper->hash;
            
            $user->get_user();
            $num = $user->num_rows;
            if ($num > 0) {
                // Generate token for accessing mail
                $user->token = $helper->generate_token();
                // Store token to user
                if ($user->set_token()) {
                    // Return token
                    http_response_code(200);
                    echo json_encode(array(
                        "message" => "Login Success!",
                        "token" => $user->token
                    ));
                }else {
                    http_response_code(503);
                    echo json_encode(array(
                        "message" => "Unable to generate token. ".$user->err_message." Please re-login!"
                    ));
                }
            }else {
                http_response_code(404);
                echo json_encode(array(
                    "message" => "User not found. Check username/password given."
                ));
            }
        }else {
            http_response_code(503);
            echo json_encode(array(
                "message" => "Unable to proceed. You give wrong username format. Username must be 'mail@mail.com'"
            ));
        }
    }else {
        http_response_code(400);
        echo json_encode(array(
            "message" => "Unable to proceed. You need username & password to login!"
        ));
    }

?>