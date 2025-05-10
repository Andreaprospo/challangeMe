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

    if(!isset($_GET["numeroUtenti"]) || empty($_GET["numeroUtenti"]))
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Parametro top non valido";
        print(json_encode($vettoreRitorno));
        return;
    }

    $numeroUtenti = $_GET["numeroUtenti"];
    $utenteCorrente = $_SESSION["utenteCorrente"];
    $gestoreDB = GestoreDB::getInstance();
    $result = $gestoreDB->getUtentiPunteggioMaggiore($numeroUtenti);

    if($result)
    {
        $vettoreRitorno["status"] = "OK";
        $vettoreRitorno["msg"] = "Utenti trovati";
        $vettoreRitorno["data"] = $result;
    }
    else
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Nessun utente";
    }
    print(json_encode($vettoreRitorno));
    return;

?>