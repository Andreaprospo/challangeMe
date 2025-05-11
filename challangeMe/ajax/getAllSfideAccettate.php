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

    if(!isset($_GET["username"]) || empty($_GET["username"]))
    {
        $username = $_SESSION["utenteCorrente"]->getUsername();
    }
    else
    {
        $username = $_GET["username"];
    }
    

    $gestoreDB = GestoreDB::getInstance();

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