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

    if(!isset($_GET["idGruppo"], $_GET["username"]))
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Parametri non validi";
        print(json_encode($vettoreRitorno));
        return;
    }

    $idGruppo = $_GET["idGruppo"];
    $username = $_GET["username"];
    $utenteCorrente = $_SESSION["utenteCorrente"];
    $usernameUtenteCorrente = $utenteCorrente->getUsername();
    $gestoreDB = GestoreDB::getInstance();
    $result = $gestoreDB->invitaUtente($idGruppo, $usernameUtenteCorrente, $username);
    if($result)
    {
        $vettoreRitorno["status"] = "OK";
        $vettoreRitorno["msg"] = "Utente invitato";
    }
    else
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Utente non invitato";
    }
    print(json_encode($vettoreRitorno));
    return;
    ?>