<?php

    class User {
        // Devine connections & table
        private $conn;
        private $t_name = "users";

        // Object properties
        public $id;
        public $username;
        public $password;
        public $token;
        public $mailjet_id;
        public $mailjet_status;

        // custom properties
        public $num_rows;
        public $err_message;

        // Constructor
        public function __construct($db) {
            $this->conn = $db;
        }

        function get_data_token() {
            $qry = "SELECT * FROM ".$this->t_name." WHERE token = '".$this->token."'";
            $res = pg_query($this->conn, $qry);

            if (!$res) {
                echo pg_last_error($this->conn);
                exit;
            }
            
            while ($row = pg_fetch_assoc($res)) {
                // Set values to property
                $this->id = $row['id'];
                $this->username = $row['username'];
                $this->password = $row['password'];
            }
        }

        function create() {
            $qry = "INSERT INTO ".$this->t_name."(username, password) VALUES ('".$this->username."', '".$this->password."')";
            $res = pg_query($this->conn, $qry);

            if (!$res) {
                $this->err_message = pg_last_error($this->conn);
                return false;
            }
            return true;
        }

        function get_user() {
            $qry = "SELECT * FROM ".$this->t_name." WHERE username='".$this->username."' AND password='".$this->password."'";
            $res = pg_query($this->conn, $qry);
            
            $this->num_rows = pg_num_rows($res);
            while ($row = pg_fetch_assoc($res)) {
                // Set values to property
                $this->id = $row['id'];
                $this->username = $row['username'];
                $this->password = $row['password'];
            }
        }

        function set_token() {
            $qry = "UPDATE ".$this->t_name." SET token='".$this->token."'";
            $res = pg_query($this->conn, $qry);

            if (!$res) {
                $this->err_message = pg_last_error($this->conn);
                return false;
            }
            return true;
        }

        function set_mailjet() {
            $qry = "UPDATE ".$this->t_name." SET mailjet_id=".$this->mailjet_id.", mailjet_status='".$this->mailjet_status."'";
            $res = pg_query($this->conn, $qry);

            if (!$res) {
                $this->err_message = pg_last_error($this->conn);
                return false;
            }
            return true;
        }
    }

?>