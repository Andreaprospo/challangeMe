<?php

    require_once "../Classi/Utente.php";
    require_once "../Classi/GestoreDB.php";

    $pathIniziale = "../Immagini/";
    $vettoreRitorno = null;
    

    if(!isset($_SESSION))
        session_start();

    if(!isset($_SESSION["utenteCorrente"]))
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Utente non loggato";
        print(json_encode($vettoreRitorno));
        return;
    }
    $utenteCorrente = $_SESSION["utenteCorrente"];
    $gestoreDB = GestoreDB::getInstance();

    if(isset($_POST["descrizione"]) && !empty($_POST["descrizione"]))
    {
        $descrizione = $_POST["descrizione"];
        $result = $gestoreDB->modificaDescrizioneProfilo($utenteCorrente->getUsername(), $descrizione);
        if($result) 
        {
            $vettoreRitorno["status"] = "OK";
            $vettoreRitorno["msg"] = "Modifica avvenuta con successo";
        } 
        else 
        {
            $vettoreRitorno["status"] = "ERR";
            $vettoreRitorno["msg"] = "Modifica non avvenuta";
        }
    }

    if (isset($_FILES["pathFoto"])) 
    {
        $nomeFoto = $_FILES["pathFoto"]["name"];
        $pathFinale = $pathIniziale . $nomeFoto;

        if (!is_dir($pathIniziale)) {
            mkdir($pathIniziale, 0777, true); // Crea la cartella se non esiste
        }

        if (move_uploaded_file($_FILES["pathFoto"]["tmp_name"], $pathFinale)) {
            $oldPathFoto = $gestoreDB->getAllUsernameInfo($utenteCorrente->getUsername())["pathFotoProfilo"];
            $result = $gestoreDB->modificaPathFotoProfilo($utenteCorrente->getUsername(), "Immagini/" . $nomeFoto);
            if ($oldPathFoto != null && !empty($oldPathFoto) && $result)
            {
                if (file_exists($oldPathFoto)) {
                    unlink($oldPathFoto); // Elimina la foto precedente se esiste
                }
                $vettoreRitorno["status"] = "OK";
                $vettoreRitorno["msg"] = "Modifica avvenuta con successo";
            }
            else
            {
                $vettoreRitorno["status"] = "ERR";
                $vettoreRitorno["msg"] = "Modifica non avvenuta";
            }
            print(json_encode($vettoreRitorno));
            return;
        } else {
            $vettoreRitorno["status"] = "ERR";
            $vettoreRitorno["msg"] = "Caricamento fallito";
            print(json_encode($vettoreRitorno));
            return;
        }
    }

    print_r(json_encode($vettoreRitorno));
    return;





?>