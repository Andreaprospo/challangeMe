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

    if(!isset($_GET["idGruppo"]))
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "ID gruppo non valido";
        print(json_encode($vettoreRitorno));
        return;
    }

    $idGruppo = $_GET["idGruppo"];
    $gestoreDB = GestoreDB::getInstance();
    $result = $gestoreDB->getAllMessaggi($idGruppo);

    if(count($result) == 0)
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Nessun messaggio trovato";
    }
    else
    {
        $vettoreRitorno["status"] = "OK";
        $vettoreRitorno["data"] = $result;
    }
    print(json_encode($vettoreRitorno));
    return;
?>