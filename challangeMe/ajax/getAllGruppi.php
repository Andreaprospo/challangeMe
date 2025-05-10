<?php

    require_once("../Classi/GestoreDB.php");
    require_once("../Classi/Utente.php");

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

    $gestoreDB = GestoreDB::getInstance();
    $utenteCorrente = $_SESSION["utenteCorrente"];
    $username = $utenteCorrente->getUsername();

    $result = $gestoreDB->getAllGruppi($username);
    if(sizeof($result) == 0)
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Nessun gruppo trovato";
    }
    else
    {
        $vettoreRitorno["status"] = "OK";
        $vettoreRitorno["data"] = $result;
    }
    print(json_encode($vettoreRitorno));
    return;
?>