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

    if(!isset($_GET["username"]) || empty($_GET["username"]))
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Parametri non validi";
        print(json_encode($vettoreRitorno));
        return;
    }

    $username = $_GET["username"];
    $utenteCorrente = $_SESSION["utenteCorrente"];
    $usernameCorrente = $utenteCorrente->getUsername();
    $gestoreDB = GestoreDB::getInstance();
    $result = $gestoreDB->checkIfIsSubscribed($username, $usernameCorrente);
    
    if($result)
    {
        $vettoreRitorno["status"] = "OK";
        $vettoreRitorno["msg"] = "L'utente segue il profilo";
    }
    else
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "L'utente non segue il profilo";
    }
    print(json_encode($vettoreRitorno));
    return;


?>