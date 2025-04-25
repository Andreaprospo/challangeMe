<?php
    require_once("../Classi/GestoreDB.php");
    require_once("../Classi/Utente.php");
    
    $vettoreRitorno = null;
    if(!isset($_SESSION))
        session_start();

    if (!isset($_SESSION["utenteCorrente"]))
    {
        $vettoreRitorno["status"] = "ERR";
        print(json_encode($vettoreRitorno));
        return;
    }
    $utenteCorrente = $_SESSION["utenteCorrente"];
    $gestoreDB = GestoreDB::getInstance();   
    $sfideAccettate = $gestoreDB->getAllNuoveSfide($utenteCorrente->getUsername());
    
    if($sfideAccettate != null)
    {
        $vettoreRitorno["status"] = "OK";
        $vettoreRitorno["data"] = $sfideAccettate;
        
        print(json_encode($vettoreRitorno));
        return;
    }
    
    $vettoreRitorno["status"] = "ERR";
    $vettoreRitorno["msg"] = "Nessuna nuova sfida trovata";
    print(json_encode($vettoreRitorno));
    return;
?>