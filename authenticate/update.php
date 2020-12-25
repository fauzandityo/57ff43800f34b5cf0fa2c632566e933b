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

    // Retrieve data
    $data = json_decode(file_get_contents("php://input"));

    // Check token
    if (!empty($data->token)) {
        // Set token to user object
        $user->token = $data->token;
        // Check the token given
        $user->get_data_token();
        if ($user->id != null) {
            // Set data to object properties for update
            $user->username = ($data->username == '') ? $user->username : $data->username;
            $user->password = ($data->password == '') ? $user->password : $data->password;
        }else {
            http_response_code(400);
            echo json_encode(array(
                "message" => "Unable to proceed. You don't have access this data, check your access token."
            ));
        }
    }else {
        http_response_code(400);
        echo json_encode(array(
            "message" => "Unable to proceed. You don't have access this data, check your access token."
        ));
    }

?>