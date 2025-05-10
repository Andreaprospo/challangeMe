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

    $utenteCorrente = $_SESSION["utenteCorrente"];
    $username = $utenteCorrente->getUsername();
    $gestoreDB = GestoreDB::getInstance();
    $result = $gestoreDB->getAllInviti($username);
    if(count($result) == 0)
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Nessun invito trovato";
    }
    else
    {
        $vettoreRitorno["status"] = "OK";
        $vettoreRitorno["data"] = $result;
    }
    print(json_encode($vettoreRitorno));
    return;
?>