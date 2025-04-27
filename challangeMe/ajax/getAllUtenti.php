<?php

    require_once "../Classi/GestoreDB.php";
    require_once "../Classi/Utente.php";

    if(!isset($_SESSION))
        session_start();

    if (!isset($_SESSION["utenteCorrente"]))
    {
        $vettoreRitorno["status"] = "ERR";
        print(json_encode($vettoreRitorno));
        return;
    }
    $vettoreRitorno = null;
    $utenteCorrente = $_SESSION["utenteCorrente"];
    $gestoreDB = GestoreDB::getInstance();
    $result = $gestoreDB->getAllUtentiNonSeguiti($utenteCorrente->getUsername());

    if($result == null)
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Nessun utente trovato";
        print(json_encode($vettoreRitorno));
        return;
    }
    $vettoreRitorno["status"] = "OK";
    $vettoreRitorno["data"] = $result;
    print(json_encode($vettoreRitorno));
    return;
?>