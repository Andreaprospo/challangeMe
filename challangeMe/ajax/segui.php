<?php
    require_once "../Classi/GestoreDB.php";
    require_once "../Classi/Utente.php";

    $vettoreRitorno = null;

    if(!isset($_SESSION))
        session_start();

    if (!isset($_SESSION["utenteCorrente"]))
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Utente non loggato";
        print(json_encode($vettoreRitorno));
        return;
    }

    if(!isset($_GET["username"]) || empty($_GET["azione"]))
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Parametri non validi";
        print(json_encode($vettoreRitorno));
        return;
    }

    $username = $_GET["username"];
    $azione = $_GET["azione"];
    $utenteCorrente = $_SESSION["utenteCorrente"];
    $gestoreDB = GestoreDB::getInstance();

    if($azione == "true")
        $result =  $gestoreDB->segui($username, $utenteCorrente->getUsername());
    else
        $result =  $gestoreDB->smettiDiSeguire($username, $utenteCorrente->getUsername());
   
    if($result)
    {
        $vettoreRitorno["status"] = "OK";
        $vettoreRitorno["msg"] = "Operazione completata con successo";
    }
    else
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Operazione non completata";
    }
    print(json_encode($vettoreRitorno));
    return;


?>