<?php

    require_once("../Classi/GestoreDB.php");
    require_once("../Classi/Utente.php");

    if(!isset($_SESSION))
        session_start();
    $vettoreRitorno = null;
    if(!isset($_SESSION["utenteCorrente"]))
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Utente non loggato";
        print(json_encode($vettoreRitorno));
        return;
    }
    if(!isset($_GET["idSfida"]) || empty($_GET["idSfida"]))
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "ID sfida non specificato";
        print(json_encode($vettoreRitorno));
        return;
    }
    
    $idSfida = $_GET["idSfida"];    
    $gestoreDB = GestoreDB::getInstance();
    $username = $_SESSION["utenteCorrente"]->getUsername();
    $result = $gestoreDB->accettaSfida($username, $idSfida);
    
    if($result)
    {
        $vettoreRitorno["status"] = "OK";
        $sfida = $gestoreDB->getSfida($idSfida);
        $vettoreRitorno["data"] = $sfida;        
        
        print(json_encode($vettoreRitorno));
        return;
    }
    
    $vettoreRitorno["status"] = "ERR";
    $vettoreRitorno["msg"] = "Nessuna sfida accettata";
    print(json_encode($vettoreRitorno));

?>