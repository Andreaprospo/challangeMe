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
    
    $gestoreDB = GestoreDB::getInstance();
    $username = $_SESSION["utenteCorrente"]->getUsername();
    
    $sfideAccettate = $gestoreDB->getSfideAccettate($username);
    
    if($sfideAccettate != null)
    {
        $vettoreRitorno["status"] = "OK";
        $vettoreRitorno["data"] = $sfideAccettate;
        print(json_encode($vettoreRitorno));
        return;
    }
    
    $vettoreRitorno["status"] = "ERR";
    $vettoreRitorno["msg"] = "Nessuna sfida accettata";
    print(json_encode($vettoreRitorno));

?>