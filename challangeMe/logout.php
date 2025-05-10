<?php
    if(!isset($_SESSION))
        session_start();
    $_SESSION["utenteCorrente"] = null;
    session_destroy();
    header("Location: index.php?messaggio=Logout effettuato");
    exit;
?>