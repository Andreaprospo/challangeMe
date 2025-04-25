<?php

    class Utente
    {
        private $username;
        private $password;
        private $mail;
        private $immagineProfilo;

        function __construct($username, $password, $email, $immagineProfilo)
        {
            $this->username = $username;
            $this->password = $password;
            $this->email = $email;
            $this->immagineProfilo = $immagineProfilo;
        }

        public function getUsername()
        {
            return $this->username;
        }

        static public function parse($data)
        {
            return new Utente($data["username"], $data["password"], $data["mail"],  null);
        }
    }

?>
