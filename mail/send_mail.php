<?php

    // Headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    // Instantiate tools
    include_once "../config/database.php";
    include_once "../utility/helper.php";
    include_once "../utility/mailjet.php";

    // Instantiate objects needed
    include_once "../objects/mail.php";
    include_once "../objects/user.php";

    $database = new Database();
    $db = $database->getConnection();

    // Initialize objects
    $user = new User($db);
    $mail = new Mail($db);
    $helper = new Helper();
    $mailjet = new MailJet();

    // Retrieve data
    $data = json_decode(file_get_contents("php://input"));

    // Initialize variables needed
    $proceed = true;
    $status = '';

    // Check token
    if (!empty($data->token) &&
        !empty($data->mail_to) &&
        !empty($data->mail_cc) &&
        !empty($data->mail_subject) &&
        !empty($data->mail_body) &&
        !empty($data->mail_attachment)) {
        // Set token to user object
        $user->token = $data->token;
        // Check token given
        $user->get_data_token();
        if ($user->id != null) {
            // Check Mailjet validated
            $mailjet->id_sender = $user->mailjet_id;
            $validated = $mailjet->check_validate();
            if ($validated['Data'][0]['Status'] == 'Active') {
                // Update to mailjet
                if ($user->mailjet_status != $validated['Data'][0]['Status']) {
                    $user->mailjet_status = $validated['Data'][0]['Status'];
                    $user->set_mailjet();
                }
                // Set values to object properties
                $mail->email_from = $user->username;
                $mail->email_to = $data->mail_to;
                $mail->email_cc = $data->mail_cc;
                $mail->email_subject = $data->mail_subject;
                $mail->email_message = $data->mail_body;
                $mail->create_date = date('Y-m-d H:i:s');
                $mail->create_uid = $user->id;
                if ($data->mail_attachment != '') {
                    if (!$helper->base64_valid($data->mail_attachment)) {
                        $proceed = false;
                        $status = "File attachment is not base64 string.";
                    }
                    $mail->email_attachment = $data->mail_attachment;
                    // mime type
                    $mime = explode('/', $helper->mime_type)[1];
                    $mailjet->e_att_name = 'NoNameFile.'.$mime;
                    $mailjet->e_att_mime = $helper->mime_type;
                }
                // Send mail
                $send = $mail->send_mail();
                // echo $send;
                if ($mailjet->http_status == 200) {
                    $mail->store_mail();
                    http_response_code(200);
                    echo json_encode(array(
                        "message" => "Email Sent!"
                    ));
                }else {
                    http_response_code($mailjet->http_status);
                    echo $send;
                }
            }else {
                http_response_code(503);
                echo json_encode(array(
                    "message" => "Unable to proceed. You need to validate your email."
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
            "message" => "Unable to proceed. Data is incomplete."
        ));
    }
?>