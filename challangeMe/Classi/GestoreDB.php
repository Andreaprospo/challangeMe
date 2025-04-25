<?php

    class GestoreDB
    {
        private $db;
        private $host = 'localhost';
        private $user = 'root';
        private $password = '';
        private $database = 'challengeMe';

        static private $instance = null;
        function __construct()
        {
            $this->db = new mysqli($this->host, $this->user, $this->password, $this->database);
            if ($this->db->connect_error) {
                die("Connection failed: " . $this->db->connect_error);
            }
        }

        static function getInstance()
        {
            if (self::$instance === null) {
                $instance = new GestoreDB();
            }
            return $instance;
        }

        function login($identificativo, $password)
        {
            $password = md5($password);
            if(str_contains($identificativo,"@"))
                $stmt = $this->db->prepare("SELECT * FROM utenti WHERE mail = ? AND password = ?");
            else
                $stmt = $this->db->prepare("SELECT * FROM utenti WHERE username = ? AND password = ?");

            $stmt->bind_param("ss", $identificativo, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return true; // Login successful
            } else {
                return false; // Login failed
            }
        }   
    
        function registrati($username, $password, $email)
        {
            $password = md5($password);
            $stmt = $this->db->prepare("INSERT INTO utenti (username, password, mail) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $password, $email);
            try
            {
                if ($stmt->execute()) {
                    return "Registrazione eseguita con successo"; // Registration successful
                } else {
                    return "Errore sconosciuto";
                }
            }
            catch(Exception $e)
            {
                if($this->getUtenteFromMail($email))
                {
                    return "Mail duplicata"; // Registration failed, email already exists
                }
                else
                {
                    if($this->getUtenteFromUsername($username))
                    {
                        return "Username duplicato"; // Registration failed, username already exists
                    }
                    else
                    {
                        return "Errore sconosciuto"; // Registration failed for other reasons
                    }
                }
            }
        }

        function getUtenteFromMail($mail)
        {
            $stmt = $this->db->prepare("SELECT * FROM utenti WHERE mail = ?");
            $stmt->bind_param("s", $mail);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return true; // Login successful
            } else {
                return false; // Login failed
            }
        }

        function getUtenteFromUsername($username)
        {
            $stmt = $this->db->prepare("SELECT * FROM utenti WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return true; // Login successful
            } else {
                return false; // Login failed
            }
        }


    }
?>