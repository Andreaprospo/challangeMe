<?php
    require_once "../Classi/Utente.php";
    require_once "../Classi/GestoreDB.php";

    $vettoreRitorno = [];
    if(!isset($_SESSION))
        session_start();

    if($_SESSION["utenteCorrente"] == null) {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Utente non loggato";
        print(json_encode($vettoreRitorno));
        return;
    }
    $utenteCorrente = $_SESSION["utenteCorrente"];
    $gestoreDB = GestoreDB::getInstance();
    $result = $gestoreDB->getAllSeguiti($utenteCorrente->getUsername());
    if($result == null)
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Nessun seguito trovato";
        print(json_encode($vettoreRitorno));
        return;
    }
    $vettoreRitorno["status"] = "OK";
    $vettoreRitorno["data"] = $result;
    print(json_encode($vettoreRitorno));
    return;

?>