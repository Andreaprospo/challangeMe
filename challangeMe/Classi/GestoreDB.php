<?php

    class GestoreDB
    {
        private $db;
        // private $host = 'localhost';
        // private $user = 'uaulumjp_proserpioandrea';
        // private $password = '~VS81uXeUy^6';
        // private $database = 'uaulumjp_challengeMe';

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

        function getUtente($identificativo)
        {
            if(str_contains($identificativo,"@"))
                $stmt = $this->db->prepare("SELECT * FROM utenti WHERE mail = ?");
            else
                $stmt = $this->db->prepare("SELECT * FROM utenti WHERE username = ?");

            $stmt->bind_param("s", $identificativo);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc(); // Login successful
            } else {
                return null; // Login failed
            }
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
            $this->createUtente($username, $password, $email);
            return $this->createProfilo($username);
            if($this->createProfilo($username) && $this->createUtente($username, $password, $email) == "Registrazione eseguita con successo")
            {
                return "Registrazione eseguita con successo"; // Registration successful
            }
            else
            {
                return "Registrazione fallita"; // Registration failed
            }
        }
        function createSfida($username, $descrizione, $dataInizio, $oraInizio, $dataFine, $oraFine, $pathFoto, $punteggio)
        {
            $stmt = $this->db->prepare("INSERT INTO sfide (usernameUtenteCreatore, descrizione, dataInizio, oraInizio, dataFine, oraFine, pathFotoRicompensa, punteggioRicompensa) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $username, $descrizione, $dataInizio, $oraInizio, $dataFine, $oraFine, $pathFoto, $punteggio);
            if ($stmt->execute()) {
                return true; // Registration successful
            } else {
                return false; // Registration failed
            }
        }
        private function createProfilo($username)
        {
            $pathFotoProfilo = "Immagini/default.png";
            $stmt = $this->db->prepare("INSERT INTO profili (username, pathFotoProfilo) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $pathFotoProfilo);
            if ($stmt->execute()) {
                return true; // Registration successful
            } else {
                return false; // Registration failed
            }
        }
        private function createUtente($username, $password, $email)  
        {
            //deve creare anche un profilo oltre all'utente 
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
        function getAllNuoveSfide($username)
        {
            $stmt = $this->db->prepare("SELECT *
                    FROM sfide
                    WHERE id NOT IN (
                        SELECT idSfida
                        FROM accettare_sfide
                        WHERE usernameUtente = ?
                    )
                    AND TIMESTAMP(dataFine, oraFine) > NOW();
            ");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_all(MYSQLI_ASSOC); // Login successful
            } else {
                return null; // Login failed
            }
        }
        function accettaSfida($username, $idSfida)
        {
            $stmt = $this->db->prepare("INSERT INTO accettare_sfide (usernameUtente, idSfida) VALUES (?, ?)");
            $stmt->bind_param("si", $username, $idSfida);
            if ($stmt->execute()) {
                return true; // Registration successful
            } else {
                return false; // Registration failed
            }
        }
        function getSfida($idSfida)
        {
            $stmt = $this->db->prepare("SELECT * FROM sfide WHERE id = ?");
            $stmt->bind_param("i", $idSfida);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc(); // Login successful
            } else {
                return null; // Login failed
            }
        }
        function getSfideAccettate($username)
        {
            $stmt = $this->db->prepare("SELECT sfide.*
            FROM sfide
            JOIN accettare_sfide ON sfide.id = accettare_sfide.idSfida
            WHERE accettare_sfide.usernameUtente = ?
              AND sfide.id NOT IN (
                SELECT idSfida
                FROM traguardi
                WHERE usernameVincitore = ?
              )
            ");
            $stmt->bind_param("ss", $username, $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_all(MYSQLI_ASSOC); // Login successful
            } else {
                return null; // Login failed
            }
        }
        function getAllSeguiti($username)
        {
            $stmt = $this->db->prepare(query: "SELECT usernameSeguito username FROM seguire
                    WHERE usernameSeguente = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_all(MYSQLI_ASSOC); // Login successful
            } else {
                return null; // Login failed
            }
        }   
        function getAllUtentiNonSeguiti($username)
        {
            $stmt = $this->db->prepare("SELECT *
            FROM utenti
            WHERE username != ?
            AND username NOT IN (
                SELECT usernameSeguito
                FROM seguire
                WHERE usernameSeguente = ?
            );
            ");
            $stmt->bind_param("ss", $username, $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_all(MYSQLI_ASSOC); // Login successful
            } else {
                return null; // Login failed
            }
        }
        function segui($usernameSeguito, $usernameSeguente)
        {
            $stmt = $this->db->prepare("INSERT INTO seguire (usernameSeguito, usernameSeguente) VALUES (?, ?)");
            $stmt->bind_param("ss", $usernameSeguito, $usernameSeguente);
            if ($stmt->execute()) {
                return true; // Registration successful
            } else {
                return false; // Registration failed
            }
        }
        function smettiDiSeguire($usernameSeguito, $usernameSeguente)
        {
            $stmt = $this->db->prepare("DELETE FROM seguire WHERE usernameSeguito = ? AND usernameSeguente = ?");
            $stmt->bind_param("ss", $usernameSeguito, $usernameSeguente);
            if ($stmt->execute()) {
                return true; // Registration successful
            } else {
                return false; // Registration failed
            }
        }
        function eliminaSfida($idSfida, $username)
        {
            $stmt = $this->db->prepare("DELETE FROM accettare_sfide WHERE idSfida = ? AND usernameUtente = ?");
            $stmt->bind_param("is", $idSfida, $username);
            if ($stmt->execute()) {
                return true; // Registration successful
            } else {
                return false; // Registration failed
            }
        }
        private function aggiungiTraguardo($idSfida, $username)
        {
            $stmt = $this->db->prepare("INSERT INTO traguardi (idSfida, usernameVincitore, ora, data) VALUES (?, ?, ?, ?)");
            $fusoOrario = 'Europe/Rome';  // Puoi sostituirlo con qualsiasi fuso orario valido
            $ora = new DateTime("now", new DateTimeZone($fusoOrario));
            $ora = $ora->format("H:i:s");
            $data = date("Y-m-d");
            $stmt->bind_param("isss", $idSfida, $username, $ora, $data);
            if ($stmt->execute()) {
                return true; // Registration successful
            } else {
                return false; // Registration failed
            }
        }
        public function addPunteggio($idSfida, $username)
        {
            $stmt = $this->db->prepare("SELECT punteggioRicompensa punteggio FROM sfide WHERE id = ?");
            $stmt->bind_param("i", $idSfida);
            $result = $stmt->execute();
            $punteggio = $stmt->get_result()->fetch_assoc()["punteggio"];
            $stmt = $this->db->prepare("UPDATE profili SET punteggio = punteggio + ? WHERE username = ?");
            $stmt->bind_param("is", $punteggio, $username);
            if ($stmt->execute()) {
                return true; // Registration successful
            } else {
                return false; // Registration failed
            }
        }
        function completaSfida($idSfida, $username)
        {
            if($this->aggiungiTraguardo($idSfida, $username) && $this->addPunteggio($idSfida, $username))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        function getAllSfideCompletate($username)
        {
            $stmt = $this->db->prepare("SELECT *, traguardi.data dataCompletamento, traguardi.ora oraCompletamento
            FROM sfide
            JOIN traguardi ON sfide.id = traguardi.idSfida
            WHERE traguardi.usernameVincitore = ?
            ");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_all(MYSQLI_ASSOC); // Login successful
            } else {
                return null; // Login failed
            }
        }
        function getAllUsernameInfo($username)
        {
            $stmt = $this->db->prepare("SELECT 
            utenti.username, profili.pathFotoProfilo, profili.descrizione
            FROM utenti
            JOIN profili ON utenti.username = profili.username
            WHERE utenti.username = ?;
            ;
        ");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc(); // Login successful
            } else {
                return null; // Login failed
            }
        }
        function modificaProfilo($username, $pathFotoProfilo, $descrizione)
        {
            $stmt = $this->db->prepare("UPDATE profili SET pathFotoProfilo = ?, descrizione = ? WHERE username = ?");
            $stmt->bind_param("sss", $pathFotoProfilo, $descrizione, $username);
            if ($stmt->execute()) {
                return true; // Registration successful
            } else {
                return false; // Registration failed
            }
        }
        function modificaDescrizioneProfilo($username, $descrizione)
        {
            $stmt = $this->db->prepare("UPDATE profili SET descrizione = ? WHERE username = ?");
            $stmt->bind_param("ss", $descrizione, $username);
            if ($stmt->execute()) {
                return true; // Registration successful
            } else {
                return false; // Registration failed
            }
        }
        function modificaPathFotoProfilo($username, $pathFotoProfilo)
        {
            $stmt = $this->db->prepare("UPDATE profili SET pathFotoProfilo = ? WHERE username = ?");
            $stmt->bind_param("ss", $pathFotoProfilo, $username);
            if ($stmt->execute()) {
                return true; // Registration successful
            } else {
                return false; // Registration failed
            }
        }
        function getUtentiPunteggioMaggiore($numeroTop)
        {
            $stmt = $this->db->prepare("SELECT username, punteggio, pathFotoProfilo FROM profili ORDER BY punteggio DESC LIMIT ?");
            $stmt->bind_param("i", $numeroTop);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_all(MYSQLI_ASSOC); // Login successful
            } else {
                return null; // Login failed
            }
        }
        function getAllGruppi($username)
        {
            $stmt = $this->db->prepare("SELECT *
            FROM gruppi
            WHERE id IN (
                SELECT idGruppo
                FROM partecipazioni_gruppi
                WHERE usernameUtente = ?
            )
            ;
            ");
            $stmt->bind_param("s", $username);
            $stmt->execute();

            $result = $stmt->get_result();
            $gruppi = [];
            while ($row = $result->fetch_assoc()) {
                $gruppi[] = $row;
            }   
            return $gruppi;
        }
        function getAllMessaggi($idGruppo)
        {
            $stmt = $this->db->prepare("SELECT *
            FROM messaggi
            WHERE idGruppo = ?
            ;
            ");
            $stmt->bind_param("i", $idGruppo);
            $stmt->execute();

            $result = $stmt->get_result();
            $messaggi = [];
            while ($row = $result->fetch_assoc()) {
                $messaggi[] = $row;
            }   
            return $messaggi;
        }
        function salvaMessaggio($idGruppo, $username, $messaggio)
        {
            $data = date("Y-m-d");
            $ora = date("H:i:s");
            $stmt = $this->db->prepare("INSERT INTO messaggi (testo, data,  ora, idGruppo, usernameUtente) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssis", $messaggio, $data, $ora, $idGruppo, $username);
            if ($stmt->execute()) {
                return true; // Registration successful
            } else {
                return false; // Registration failed
            }
        }
        function getAllInviti($username)
        {
            $stmt = $this->db->prepare("SELECT gruppi.nome nomeGruppo, usernameUtenteInvitante, gruppi.id idGruppo
            FROM inviti_gruppo
            JOIN gruppi ON inviti_gruppo.idGruppo = gruppi.id
            WHERE usernameUtenteInvitato = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $inviti = [];
            while ($row = $result->fetch_assoc()) {
                $inviti[] = $row;
            }
            return $inviti;
        }
        function eliminaInvito($username, $idGruppo)
        {
            $stmt = $this->db->prepare("DELETE FROM inviti_gruppo WHERE usernameUtenteInvitato = ? AND idGruppo = ?");
            $stmt->bind_param("si", $username, $idGruppo);
            if ($stmt->execute()) {
                return true; // Registration successful
            } else {
                return false; // Registration failed
            }
        }
        function accettaInvito($username, $idGruppo)
        {
            $stmt = $this->db->prepare("INSERT INTO partecipazioni_gruppi (usernameUtente, idGruppo) VALUES (?, ?)");
            $stmt->bind_param("si", $username, $idGruppo);
            if ($stmt->execute()) {
                if($this->eliminaInvito($username, $idGruppo))
                    return true; // Registration successful
                return false; // Registration failed
            } else {
                return false; // Registration failed
            }
        }
        function getAllUtentiNonPartecipanti($idGruppo)
        {
            $stmt = $this->db->prepare("SELECT *
            FROM utenti
            WHERE username NOT IN (
                SELECT usernameUtente
                FROM partecipazioni_gruppi
                WHERE idGruppo = ?
            )
            AND username NOT IN (
                SELECT usernameUtenteInvitato
                FROM inviti_gruppo
                WHERE idGruppo = ?
            )
            ;
            ");
            $stmt->bind_param("ii", $idGruppo, $idGruppo);
            $stmt->execute();

            $result = $stmt->get_result();
            $utenti = [];
            while ($row = $result->fetch_assoc()) {
                $utenti[] = $row;
            }   
            return $utenti;
        }
        function invitaUtente($idGruppo, $usernameInvitante, $usernameInvitato)
        {
            $stmt = $this->db->prepare("INSERT INTO inviti_gruppo (usernameUtenteInvitato, idGruppo, usernameUtenteInvitante) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $usernameInvitato, $idGruppo, $usernameInvitante);
            if ($stmt->execute()) {
                return true; // Registration successful
            } else {
                return false; // Registration failed
            }
        }
        function creaGruppo($nomeGruppo, $username)
        {
            $data = date("Y-m-d");
            $stmt = $this->db->prepare("INSERT INTO gruppi (nome,data, usernameUtenteCreatore) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nomeGruppo, $data, $username);
            if ($stmt->execute()) {
                $idGruppo = $this->db->insert_id;
                if($this->createPartecipazioneGruppo($username, $idGruppo))
                    return true; // Registration successful
                return false; // Registration failed
            } else {
                return false; // Registration failed
            }
        }

        function createPartecipazioneGruppo($username, $idGruppo)
        {
            $stmt = $this->db->prepare("INSERT INTO partecipazioni_gruppi (usernameUtente, idGruppo) VALUES (?, ?)");
            $stmt->bind_param("si", $username, $idGruppo);
            if ($stmt->execute()) {
                return true; // Registration successful
            } else {
                return false; // Registration failed
            }
        }

        function checkIfUsernameExist($idGruppo)
        {
            $stmt = $this->db->prepare("SELECT * FROM utenti WHERE username = ?");
            $stmt->bind_param("s", $idGruppo);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return true; // Login successful
            } else {
                return false; // Login failed
            }
        }

        function checkIfIsSubscribed($username, $usernameCorrente)
        {
            $stmt = $this->db->prepare("SELECT * FROM seguire WHERE usernameSeguito = ? AND usernameSeguente = ?");
            $stmt->bind_param("ss", $username, $usernameCorrente);
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