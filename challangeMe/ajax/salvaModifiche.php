<?php

    require_once "../Classi/Utente.php";
    require_once "../Classi/GestoreDB.php";

    $vettoreRitorno = [];
    if(!isset($_SESSION))
        session_start();

    if(!isset($_SESSION["utenteCorrente"]))
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Utente non loggato";
        print(json_encode($vettoreRitorno));
        return;
    }

    if(!isset($_GET["pathFotoProfilo"]) || !isset($_GET["descrizione"]))
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Parametri non validi";
        print(json_encode($vettoreRitorno));
        return;
    }
    $pathFotoProfilo = $_GET["pathFotoProfilo"];
    $descrizione = $_GET["descrizione"];
    $utenteCorrente = $_SESSION["utenteCorrente"];
    $gestoreDB = GestoreDB::getInstance();
    $result = $gestoreDB->modificaProfilo($utenteCorrente->getUsername(), $pathFotoProfilo, $descrizione);

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
    print(json_encode($vettoreRitorno));
    return;

?>