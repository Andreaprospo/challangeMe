<?php
    require_once("../Classi/GestoreDB.php");

    $vettoreRitorno = null;
    if(!isset($_GET["username"], $_GET["mail"], $_GET["password"]) || empty($_GET["username"]) || empty($_GET["password"]) || empty($_GET["mail"]))
    {
        $vettoreRitorno["status"] = "ERR";
        $vettoreRitorno["msg"] = "Parametri non validi";
        print(json_encode($vettoreRitorno));
        return;
    }

    $gestoreDB = GestoreDB::getInstance();
    $username = $_GET["username"];
    $mail = $_GET["mail"];
    $password = $_GET["password"];

    $messaggio = $gestoreDB->registrati($username, $password, $mail);
    if($messaggio == "Registrazione eseguita con successo")
        $vettoreRitorno["status"] = "OK";
    else
        $vettoreRitorno["status"] = "ERR";

    $vettoreRitorno["msg"] = $messaggio;
    print(json_encode($vettoreRitorno));
    return;
?>