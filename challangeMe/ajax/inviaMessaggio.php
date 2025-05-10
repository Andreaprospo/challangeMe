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

    if(!isset($_GET["idGruppo"], $_GET["messaggio"]))
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Parametri non validi";
        print(json_encode($vettoreRitorno));
        return;
    }

    $idGruppo = $_GET["idGruppo"];
    $messaggio = $_GET["messaggio"];
    $utenteCorrente = $_SESSION["utenteCorrente"];
    $username = $utenteCorrente->getUsername();
    $gestoreDB = GestoreDB::getInstance();
    $result = $gestoreDB->salvaMessaggio($idGruppo,$username, $messaggio);
    if($result)
    {
        $vettoreRitorno["status"] = "OK";
        $vettoreRitorno["msg"] = "Messaggio inviato";
    }
    else
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Errore durante il salvataggio del messaggio";
    }
    print(json_encode($vettoreRitorno));
    return;

?>