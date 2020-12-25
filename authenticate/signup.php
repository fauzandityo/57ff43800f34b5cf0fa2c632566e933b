<?php

    // Headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    // Instantiate tools
    include_once "../config/database.php";
    include_once "../utility/helper.php";
    include_once "../utility/mailjet.php";

    // Instantiate user object
    include_once "../objects/user.php";

    $database = new Database();
    $db = $database->getConnection();

    // Initialize object needed
    $user = new User($db);
    $helper = new Helper();
    $mailjet = new MailJet();

    // Retrieve data
    $data = json_decode(file_get_contents("php://input"));

    // Check if data empty
    if (!empty($data->username) &&
        !empty($data->password)) {
        // Check username format (must an email)
        if ($helper->email_regex($data->username)) {
            // Hash password given
            $helper->pass = $data->password;
            $helper->hash_pass();
            // Set values given to user properties
            $user->username = $data->username;
            $user->password = $helper->hash;
            // Create user
            if ($user->create()) {
                // Add email to mailjet sender
                $mailjet->email = $user->username;
                $res = $mailjet->set_sender();
                if ($res['Data'][0]['ID']) {
                    // Set sender id
                    $mailjet->id_sender = $res['Data'][0]['ID'];
                    // Update to user data
                    $user->mailjet_id = $res['Data'][0]['ID'];
                    $user->mailjet_status = $res['Data'][0]['Status'];
                    // Call update
                    $user->set_mailjet();
                    // Validate email
                    $mailjet->validate_sender();
                    // set success response
                    http_response_code(200);
                    echo json_encode(array(
                        "message" => "User has been created! You need to validate your email on mailjet to start send email."
                    ));
                }else {
                    // Send failed message
                    http_response_code(503);
                    echo $res;
                }
            }else {
                http_response_code(503);
                echo json_encode(array(
                    "message" => "Failed to create user! ".$user->err_message
                ));
            }
        }else {
            http_response_code(503);
            echo json_encode(array(
                "message" => "Unable to proceed. You give wrong username format! Username must be 'mail@mail.com'"
            ));
        }
    }else {
        http_response_code(400);
        echo json_encode(array(
            "message" => "Unable to proceed. You need to send username & password to signup!"
        ));
    }

?>