<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/styleChat.css">
        <title>Document</title>
    </head>

    <body>
        <div id="divGruppi"></div>
        <div id="divChat">
            <div id="divButton"></div>
            <div id="divMessaggi"></div>
            <div id="divInterfaccia"></div>
        </div>
        <div id="divInviti"></div>
        <div id = "divLogout">
            <button><a href="logout.php">Logout</a></button>
        </div>
        <?php
        require_once("footer.php");
        ?>

    </body>

</html>
<script>

    document.addEventListener("DOMContentLoaded", function () {
        stampaGruppi();
        stampaInviti();
    });

    async function stampaGruppi() {
        let url = "ajax/getAllGruppi.php";
        let response = await fetch(url);
        let txt = await response.text();
        console.log(txt);
        let data = JSON.parse(txt);
        console.log(data);
        let divGruppi = document.getElementById("divGruppi");

        if (data.status == "ERR") {
            divGruppi.innerHTML = `<h2>${data.msg}</h2>`;
            return;
        }

        for (const gruppo of data.data) {
            let div = document.createElement("div");
            div.className = "divGruppo";
            div.innerHTML = `<h2>${gruppo.nome}</h2>`;
            div.id = gruppo.id;
            div.addEventListener("click", function () {
                stampaChat(gruppo.id);
            });
            divGruppi.appendChild(div);
        }

    }

    async function stampaChat(idGruppo) {
        let url = "ajax/getAllMessaggi.php?idGruppo=" + idGruppo;
        console.log(url);
        let response = await fetch(url);
        let txt = await response.text();
        console.log(txt);
        let data = JSON.parse(txt);
        console.log(data);
        let divMessaggi = document.getElementById("divMessaggi");

        divInterfaccia.innerHTML = "";
        divMessaggi.innerHTML = "";

        if (data.status == "ERR") {
            divMessaggi.innerHTML = `<h2>${data.msg}</h2>`;
            stampaInterfacciaChat(idGruppo);
            stampaBottonInviti(idGruppo);
            return;
        }


        for (const messaggio of data.data) {
            let divOrario = document.createElement("div");
            divOrario.className = "divOrario";
            divOrario.innerHTML = `<p>${messaggio.data}</p>`;
            divOrario.innerHTML += `<p>${messaggio.ora}</p>`;
            let divMessaggio = document.createElement("div");
            let divMittente = document.createElement("div");
            divMittente.className = "divMittente";
            divMittente.innerHTML = `<p>${messaggio.usernameUtente}</p>`;

            let divTesto = document.createElement("div");
            divTesto.className = "divTesto";
            divTesto.innerHTML = `<p>${messaggio.testo}</p>`;
            divMessaggio.appendChild(divMittente);
            divMessaggio.appendChild(divTesto);
            divMessaggio.appendChild(divOrario);

            divMessaggi.appendChild(divMessaggio);
        }
        stampaInterfacciaChat(idGruppo);
        stampaBottonInviti(idGruppo);
    }

    function stampaInterfacciaChat(idGruppo) {
        let divInterfaccia = document.querySelector("#divInterfaccia");
        let input = document.createElement("input");
        input.type = "text";
        input.id = "inputMessaggio";
        divInterfaccia.appendChild(input);
        let button = document.createElement("button");
        button.innerHTML = "Invia";
        button.addEventListener("click", function () {
            inviaMessaggio(idGruppo);
        });
        divInterfaccia.appendChild(input);
        divInterfaccia.appendChild(button);
    }

    async function inviaMessaggio(idGruppo) {
        let input = document.getElementById("inputMessaggio");
        let messaggio = input.value;
        let url = "ajax/inviaMessaggio.php?idGruppo=" + idGruppo + "&messaggio=" + messaggio;
        console.log(url);
        let response = await fetch(url);
        let txt = await response.text();
        console.log(txt);
        let data = JSON.parse(txt);
        console.log(data);
        if (data.status == "OK") {
            input.value = "";
            stampaChat(idGruppo);
        }
        else {
            alert(data.msg);
        }
    }

    async function stampaInviti() {
        let url = "ajax/getAllInviti.php";
        let response = await fetch(url);
        let txt = await response.text();
        console.log(txt);
        let data = JSON.parse(txt);
        console.log(data);
        let divInviti = document.getElementById("divInviti");

        if (data.status == "ERR") {
            divInviti.innerHTML = `<h2>${data.msg}</h2>`;
            return;
        }

        for (const invito of data.data) {
            let div = document.createElement("div");
            let buttonAccetta = document.createElement("button");
            let buttonRifiuta = document.createElement("button");
            buttonAccetta.innerHTML = "Accetta";
            buttonAccetta.addEventListener("click", function () {
                accettaInvito(invito.idGruppo);
            });
            buttonRifiuta.innerHTML = "Rifiuta";
            buttonRifiuta.addEventListener("click", function () {
                rifiutaInvito(invito.idGruppo);
            });

            div.className = "divInvito";
            div.innerHTML = `<h2>${invito.usernameUtenteInvitante}</h2>` + `<p>${invito.nomeGruppo}</p>`;
            div.appendChild(buttonAccetta);
            div.appendChild(buttonRifiuta);
            divInviti.appendChild(div);
        }
    }

    async function accettaInvito(idGruppo) {
        let url = "ajax/accettaInvito.php?idGruppo=" + idGruppo;
        let response = await fetch(url);
        let txt = await response.text();
        console.log(txt);
        let data = JSON.parse(txt);
        console.log(data);
        if (data.status == "OK") {
            stampaInviti();
            stampaGruppi();
        }
        else {
            alert(data.msg);
        }
    }

    async function rifiutaInvito(idGruppo) {
        let url = "ajax/rifiutaInvito.php?idGruppo=" + idGruppo;
        let response = await fetch(url);
        let txt = await response.text();
        console.log(txt);
        let data = JSON.parse(txt);
        console.log(data);
        if (data.status == "OK") {
            stampaInviti();
        }
        else {
            alert(data.msg);
        }
    }

    async function selezionaUtentiDaInvitare(idGruppo) {
        let select = document.createElement("select");
        let button = document.createElement("button");
        let url = "ajax/getAllUtentiNonPartecipanti.php?idGruppo=" + idGruppo;
        let response = await fetch(url);
        let txt = await response.text();
        console.log(txt);
        let data = JSON.parse(txt);
        console.log(data);
        if (data.status == "ERR") {
            alert(data.msg);
            return;
        }
        for (const utente of data.data) {
            let option = document.createElement("option");
            option.value = utente.username;
            option.innerHTML = utente.username;
            select.appendChild(option);
        }
        let divButton = document.getElementById("divButton");
        button.innerHTML = "Invita";
        button.addEventListener("click", function () {
            invitaUtente(idGruppo, select.value);
        });
        divButton.innerHTML = "";
        divButton.appendChild(select);
        divButton.appendChild(button);
    }

    function stampaBottonInviti(idGruppo) {
        let divButton = document.getElementById("divButton");
        divButton.innerHTML = "";
        let button = document.createElement("button");
        button.innerHTML = "Invita";
        button.addEventListener("click", function () {
            selezionaUtentiDaInvitare(idGruppo);
        });
        divButton.appendChild(button);
    }

    async function invitaUtente(idGruppo, username) {
        let url = "ajax/invitaUtente.php?idGruppo=" + idGruppo + "&username=" + username;
        console.log(url);
        let response = await fetch(url);
        let txt = await response.text();
        console.log(txt);
        let data = JSON.parse(txt);
        console.log(data);
        if (data.status == "OK") {
            alert("Utente invitato");
            stampaInviti();
            stampaGruppi();
        }
        else {
            alert(data.msg);
        }
    }

</script>