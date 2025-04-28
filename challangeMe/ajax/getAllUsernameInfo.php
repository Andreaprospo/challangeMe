<?php

    require_once "../Classi/Utente.php";
    require_once "../Classi/GestoreDB.php";

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
    $result = $gestoreDB->getAllUsernameInfo($utenteCorrente->getUsername());
    if($result == null)
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Utente non trovato";
        print(json_encode($vettoreRitorno));
        return;
    }
    $vettoreRitorno["status"] = "OK";
    $vettoreRitorno["data"] = $result;
    print(json_encode($vettoreRitorno));
    return;
?>