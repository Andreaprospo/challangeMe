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

    if(!isset($_GET["descrizione"], $_GET["dataInizio"], $_GET["oraInizio"], $_GET["dataFine"], $_GET["oraFine"], $_GET["pathFoto"], $_GET["punteggio"]) 
       || empty($_GET["descrizione"]) || empty($_GET["dataInizio"]) || empty($_GET["oraInizio"]) || empty($_GET["dataFine"]) || empty($_GET["oraFine"]) || empty($_GET["pathFoto"]) || empty($_GET["punteggio"])
       || $_GET["dataInizio"] == "null" || $_GET["oraInizio"] == "null" || $_GET["dataFine"] == "null" || $_GET["oraFine"] == "null" || $_GET["pathFoto"] == "null" || $_GET["punteggio"] == "null")
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Parametri non validi";
        print(json_encode($vettoreRitorno));
        return;
    }


    $descrizione = $_GET["descrizione"];
    $dataInizio = $_GET["dataInizio"];
    $oraInizio = $_GET["oraInizio"];
    $dataFine = $_GET["dataFine"];
    $oraFine = $_GET["oraFine"];
    $pathFoto = $_GET["pathFoto"];
    $punteggio = $_GET["punteggio"];
    $utenteCorrente = $_SESSION["utenteCorrente"];

    //TODO controllare le date e gli orari

    
    
    $gestoreDB = GestoreDB::getInstance();
    $result = $gestoreDB->createSfida($utenteCorrente->getUsername(), $descrizione, $dataInizio, $oraInizio, $dataFine, $oraFine, $pathFoto, $punteggio);

    if($result)
    {
        $vettoreRitorno["status"] = "OK";
        $vettoreRitorno["msg"] = "Sfida creata con successo";
    }
    else
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Errore nella creazione della sfida";
    }
    print(json_encode($vettoreRitorno));
    return;
?>
