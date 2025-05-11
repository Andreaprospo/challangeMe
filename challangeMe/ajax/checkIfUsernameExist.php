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
    $gestoreDB = GestoreDB::getInstance();
    $result = $gestoreDB->checkIfUsernameExist($username);
    
    if($result)
    {
        $vettoreRitorno["status"] = "OK";
        $vettoreRitorno["msg"] = "Username esistente";
    }
    else
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Username non esistente";
    }
    print(json_encode($vettoreRitorno));
    return;
?>