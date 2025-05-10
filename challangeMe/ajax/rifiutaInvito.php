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
        $vettoreRitorno["msg"] = "Parametri non validi";
        print(json_encode($vettoreRitorno));
        return;
    }

    $idGruppo = $_GET["idGruppo"];
    $gestoreDB = GestoreDB::getInstance();
    $utenteCorrente = $_SESSION["utenteCorrente"];
    $username = $utenteCorrente->getUsername();

    $result = $gestoreDB->eliminaInvito($username, $idGruppo);
    if($result)
    {
        $vettoreRitorno["status"] = "OK";
        $vettoreRitorno["msg"] = "Invito rifiutato con successo";
    }
    else
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Errore durante il rifiuto dell'invito";
    }
    print(json_encode($vettoreRitorno));
    return;
?>