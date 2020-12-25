<?php

    class MailJet {

        private $mailjet_id = '302653f4bebe44fc828b80e29741289e';
        private $mailjet_sec = 'b3db82ab59a157ec5a295c54d9e7ff23';

        // cUrl properties
        public $http_status;

        public $email_type = 'unknown';
        public $default_sender = 'false';
        public $email;
        public $id_sender;

        // Mail properties
        public $email_message;
        public $email_to;
        public $email_cc;
        public $email_from;
        public $email_subject;
        public $email_attachment;

        // Mailjet properties
        public $e_att_name;
        public $e_att_mime;

        public function set_sender() {
            // Define POST data
            $post = [
                'EmailType' => $this->email_type,
                'IsDefaultSender' => $this->default_sender,
                'Name' => '',
                'Email' => $this->email
            ];
            // Prepare curl data
            $ch = curl_init('https://api.mailjet.com/v3/REST/sender');
            curl_setopt($ch, CURLOPT_USERPWD, $this->mailjet_id.':'.$this->mailjet_sec);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

            // Hit API
            $res = curl_exec($ch);
            $this->http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            // Return response
            return $res;
        }

        public function validate_sender() {
            
            // Define POST data
            $post = [];
            // Prepare curl data
            $ch = curl_init('https://api.mailjet.com/v3/REST/sender/'.$this->id_sender.'/validate');
            curl_setopt($ch, CURLOPT_USERPWD, $this->mailjet_id.':'.$this->mailjet_sec);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

            // Hit API
            $res = curl_exec($ch);
            $this->http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            // Return response
            return $res;
        }

        public function check_validate() {
            
            // Prepare curl data
            $ch = curl_init('https://api.mailjet.com/v3/REST/sender/'.$this->id_sender);
            curl_setopt($ch, CURLOPT_USERPWD, $this->mailjet_id.':'.$this->mailjet_sec);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 0);

            // Hit API
            $res = curl_exec($ch);
            $this->http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            // Return response
            return $res;
        }

        public function send() {
            // Define POST data
            $post = [
                'Messages' => [
                    'From' => [[
                        'Email' => $this->email_from,
                        'Name' => ''
                    ]],
                    'To' => [[
                        'Email' => $this->email_to,
                        'Name' => ''
                    ]],
                    'Subject' => $this->email_subject,
                    'HTMLPart' => $this->email_message
                ]
            ];
            if ($this->attachment) {
                $post['Messages'][0]['Attachments'] = [[
                    'Filename' => $this->e_att_name,
                    'ContentType' => $this->e_att_mime,
                    'Base64Content' => $this->attachment
                ]]
            }
            // Prepare curl data
            $ch = curl_init('https://api.mailjet.com/v3.1/send');
            curl_setopt($ch, CURLOPT_USERPWD, $this->mailjet_id.':'.$this->mailjet_sec);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

            // Hit API
            $res = curl_exec($ch);
            $this->http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            // Return response
            return $res;
        }
    }

?>