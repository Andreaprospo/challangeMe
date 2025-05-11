<?php

require_once "../Classi/GestoreDB.php";
require_once "../Classi/Utente.php";

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

    if(!isset($_GET["nomeGruppo"]) || empty($_GET["nomeGruppo"]))
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Nome gruppo non valido";
        print(json_encode($vettoreRitorno));
        return;
    }

    $nomeGruppo = $_GET["nomeGruppo"];
    $utenteCorrente = $_SESSION["utenteCorrente"];
    $username = $utenteCorrente->getUsername();
    $gestoreDB = GestoreDB::getInstance();
    $result = $gestoreDB->creaGruppo($nomeGruppo, $username);

    if($result)
    {
        $vettoreRitorno["status"] = "OK";
        $vettoreRitorno["msg"] = "Gruppo creato con successo";
    }
    else
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Errore nella creazione del gruppo";
    }
    print(json_encode($vettoreRitorno));
    return;
?>