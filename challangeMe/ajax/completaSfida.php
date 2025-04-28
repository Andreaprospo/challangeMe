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

    if(!isset($_GET["idSfida"]))
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "ID sfida non valido";
        print(json_encode($vettoreRitorno));
        return;
    }

    $idSfida = $_GET["idSfida"];
    $utenteCorrente = $_SESSION["utenteCorrente"];
    $gestoreDB = GestoreDB::getInstance();
    $result = $gestoreDB->completaSfida($idSfida, $utenteCorrente->getUsername());

    if($result)
    {
        $vettoreRitorno["status"] = "OK";
        $vettoreRitorno["msg"] = "Sfida completata con successo";
    }
    else
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Sfida non completata";
    }
    print(json_encode($vettoreRitorno));
    return;
?>