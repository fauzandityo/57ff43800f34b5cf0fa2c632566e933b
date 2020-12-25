<?php

class Mail {
    // Devine connections & table
    private $conn;
    private $t_name = "email";

    // Object properties
    public $id;
    public $email_message;
    public $email_to;
    public $email_cc;
    public $email_from;
    public $email_subject;
    public $email_attachment;
    public $create_date;
    public $create_uid;

    // Helper properties
    public $num_rows;

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    function read() {
        $qry = "SELECT * FROM ".$this->t_name." WHERE create_uid = ".$this->create_uid." ORDER BY create_date DESC";
        $res = pg_query($this->conn, $qry);

        if (!$res) {
            echo pg_last_error($this->conn);
            exit;
        }

        $this->num_rows = pg_num_rows($res);

        return $res;
    }

    function send_mail() {
        include_once '../utility/mailjet.php';

        $jet = new MailJet();

        // Set properties
        $jet->email_from = $this->email_from;
        $jet->email_to = $this->email_to;
        $jet->email_cc = $this->email_cc;
        $jet->email_subject = $this->email_subject;
        $jet->email_message = $this->email_message;
        $jet->email_attachment = $this->email_attachment;

        return $jet->send();
    }

    function store_mail() {
        $qry = "INSERT INTO ".$this->t_name."(email_message, email_to, email_cc, email_from, email_subject, email_attachment, create_date, create_uid) VALUES ('".$this->email_message."', '".$this->email_to."', '".$this->email_cc."', '".$this->email_from."', '".$this->email_subject."', '".$this->email_attachment."', '".$this->email_date."', '".$this->email_uid."')";
        $res = pg_query($this->conn, $qry);

        if (!$res) {
            $this->err_message = pg_last_error($this->conn);
            return false;
        }
        return true;
    }

}

?>