<?php
    require_once "../Classi/GestoreDB.php";
    require_once "../Classi/Utente.php";

    $vettoreRitorno = null;
    if(!isset($_GET["identificativo"], $_GET["password"]) || empty($_GET["identificativo"]) || empty($_GET["password"]))
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Parametri non validi";
        print(json_encode($vettoreRitorno));
        return;
    }
    $identificativo = $_GET["identificativo"];
    $password = $_GET["password"];
    $gestoreDB = GestoreDB::getInstance();

    $gestoreDB->login($identificativo, $password);
    if($gestoreDB->login($identificativo, $password))
    {
        if(!isset($_SESSION))
            session_start();
        $_SESSION["utenteCorrente"] = Utente::parse($gestoreDB->getUtente($identificativo));
        $vettoreRitorno["status"] = "OK";
        $vettoreRitorno["msg"] = "Login effettuato con successo";
    }
    else
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Login fallito";
    }
    print(json_encode($vettoreRitorno));
    return;

?>