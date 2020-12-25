<?php

    class Database{
        // Configurations for database connection
        private $host = "192.168.1.103";
        private $port = "5432";
        private $db_name = "email_adapter";
        private $username = "postgres";
        private $password = "admin123";
        public $conn;

        // Setup connection
        public function getConnection() {
            $this->conn = null;
            
            $this->conn = pg_connect(
                "host=".$this->host." port=".$this->port. " dbname=".$this->db_name." user=".$this->username." password=".$this->password
            );

            return $this->conn;
        }
    }

?>