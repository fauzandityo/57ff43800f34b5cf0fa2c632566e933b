<?php

    // Headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    // Get database connection
    include_once "../config/database.php";

    // instantiate object
    include_once "../objects/user.php";
    include_once "../objects/mail.php";

    $database = new Database();
    $db = $database->getConnection();

    // Initialize object needed
    $user = new User($db);
    $mail = new Mail($db);

    // retrieve data
    $data = json_decode(file_get_contents("php://input"));

    // Check if token exist
    if (!empty($data->token)) {
        // Set token to user object
        $user->token = $data->token;
        // Check the token given
        $user->get_data_token();
        if ($user->id != null) {
            // Set data to object variables
            $mail->create_uid = $user->id;
            $m_data = $mail->read();
            $num = $mail->num_rows;

            if ($num > 0) {
                // Mail array
                $m_array = array();
                $m_array["records"] = array();

                // Retrieve/Fetch Data
                while ($row = pg_fetch_assoc($m_data)) {
                    $m_item=array(
                        "id" => $row['id'],
                        "email_from" => $row['email_from'],
                        "email_from_name" => $row['email_from_name'],
                        "email_to" => $row['email_to'],
                        "email_cc" => $row['email_cc'],
                        "email_subject" => $row['email_subject'],
                        "email_message" => $row['email_message'],
                        "email_attachment" => $row['email_attachment'],
                        "create_date" => $row['create_date']
                    );
                    array_push($m_array["records"], $m_item);
                }

                // Give response status
                http_response_code(200);
                // Response message
                echo json_encode($m_array);
            }else {
                http_response_code(404);

                // Response message
                echo json_encode(array(
                    "message" => "No emails yet!"
                ));
            }
        }else {
            http_response_code(400);
            echo json_encode(array(
                "message" => "Unable to proceed. You don't have access this data, check your access token"
            ));
        }
    }else {
        http_response_code(400);
        echo json_encode(array(
            "message" => "Unable to proceed. You don't have access this data, check your access token"
        ));
    }

?>