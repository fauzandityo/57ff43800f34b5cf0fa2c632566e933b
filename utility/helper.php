<?php

    class Helper {

        // Private variables
        
        // Public variables
        public $pass;
        public $hash;
        public $mime_type;
        
        public function hash_pass() {
            $this->hash = hash_pbkdf2("sha512", $this->pass, 'mech', 10000, 50);
        }

        public function email_regex($email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
            return true;
        }

        public function generate_token() {
            return bin2hex(openssl_random_pseudo_bytes(16));
        }

        public function base64_valid($data) {
            if (base64_encode(base64_decode($data, true)) == $data) {
                // Decode
                $attachment = base64_decode($data);
                $f = finfo_open();
                // Store to properties
                $this->mime_type = finfo_buffer($f, $attachment, FILEINFO_MIME_TYPE);
                // Return status
                return true;
            }
            return false;
        }

    }

?>