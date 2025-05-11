<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/styleChat.css">
    <title>Chat</title>
        <link rel="icon" type="image/png" href="icona.png">
</head>

<body>
    <div class="container-fluid">
        <div id="divGruppi">
            <h4 class="mb-3">My Groups</h4>
            <div id="groupsList"></div>
            <div id="divButtonCreaGruppo" class="mt-auto">
                <button class="btn" onclick="creaInterfacciaCreazioneGruppo()">
                    <i class="fas fa-plus-circle me-2"></i>Create Group
                </button>
            </div>
        </div>

        <div id="divChat">
            <div id="divButton" class="border-bottom"></div>
            <div id="divMessaggi">
                <div class="text-center p-5 text-muted">
                    <i class="fas fa-comments fa-3x mb-3"></i>
                    <p>Select a group to start chatting</p>
                </div>
            </div>
            <div id="divInterfaccia"></div>
        </div>

        <div id="divInviti">
            <h4 class="mb-3">Invitations</h4>
        </div>
    </div>

    <div id="divLogout">
        <button class="btn"><a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></button>
    </div>

    <?php
    require_once("footer.php");
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
            let groupsList = document.getElementById("groupsList");

            if (data.status == "ERR") {
                groupsList.innerHTML = `<div class="alert alert-warning">${data.msg}</div>`;
                return;
            }

            groupsList.innerHTML = "";
            for (const gruppo of data.data) {
                let div = document.createElement("div");
                div.className = "divGruppo";
                div.innerHTML = `<h2>${gruppo.nome}</h2>`;
                div.id = gruppo.id;
                div.addEventListener("click", function () {
                    // Remove active class from all groups
                    document.querySelectorAll('.divGruppo').forEach(el => {
                        el.classList.remove('active');
                    });
                    // Add active class to clicked group
                    div.classList.add('active');
                    stampaChat(gruppo.id);
                });
                groupsList.appendChild(div);
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
                divMessaggi.innerHTML = `<div class="alert alert-info m-3">${data.msg}</div>`;
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
            
            // Scroll to the bottom of chat
            divMessaggi.scrollTop = divMessaggi.scrollHeight;
            
            stampaInterfacciaChat(idGruppo);
            stampaBottonInviti(idGruppo);
        }

        function stampaInterfacciaChat(idGruppo) {
            let divInterfaccia = document.querySelector("#divInterfaccia");
            divInterfaccia.innerHTML = "";
            
            let inputGroup = document.createElement("div");
            inputGroup.className = "input-group";
            
            let input = document.createElement("input");
            input.type = "text";
            input.id = "inputMessaggio";
            input.className = "form-control";
            input.placeholder = "Type your message...";
            input.addEventListener("keypress", function(event) {
                if (event.key === "Enter") {
                    inviaMessaggio(idGruppo);
                }
            });
            
            let button = document.createElement("button");
            button.className = "btn";
            button.innerHTML = '<i class="fas fa-paper-plane"></i>';
            button.addEventListener("click", function () {
                inviaMessaggio(idGruppo);
            });
            
            inputGroup.appendChild(input);
            inputGroup.appendChild(button);
            divInterfaccia.appendChild(inputGroup);
        }

        async function inviaMessaggio(idGruppo) {
            let input = document.getElementById("inputMessaggio");
            let messaggio = input.value.trim();
            
            if (messaggio === "") return;
            
            let url = "ajax/inviaMessaggio.php?idGruppo=" + idGruppo + "&messaggio=" + encodeURIComponent(messaggio);
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

            // Clear previous content but keep the heading
            let heading = divInviti.querySelector('h4');
            divInviti.innerHTML = '';
            if (heading) {
                divInviti.appendChild(heading);
            }

            if (data.status == "ERR") {
                divInviti.innerHTML += `<div class="alert alert-warning">${data.msg}</div>`;
                return;
            }
            
            if (data.data.length === 0) {
                divInviti.innerHTML += `<div class="text-center text-muted p-3">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <p>No pending invitations</p>
                </div>`;
                return;
            }

            for (const invito of data.data) {
                let div = document.createElement("div");
                div.className = "divInvito";
                
                div.innerHTML = `
                    <h2>${invito.usernameUtenteInvitante}</h2>
                    <p>invites you to join</p>
                    <p class="fw-bold">${invito.nomeGruppo}</p>
                `;
                
                let buttonGroup = document.createElement("div");
                buttonGroup.className = "d-flex gap-2 mt-2";
                
                let buttonAccetta = document.createElement("button");
                buttonAccetta.innerHTML = '<i class="fas fa-check me-1"></i> Accept';
                buttonAccetta.addEventListener("click", function () {
                    accettaInvito(invito.idGruppo);
                });
                
                let buttonRifiuta = document.createElement("button");
                buttonRifiuta.innerHTML = '<i class="fas fa-times me-1"></i> Decline';
                buttonRifiuta.addEventListener("click", function () {
                    rifiutaInvito(invito.idGruppo);
                });

                buttonGroup.appendChild(buttonAccetta);
                buttonGroup.appendChild(buttonRifiuta);
                div.appendChild(buttonGroup);
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
            let url = "ajax/getAllUtentiNonPartecipanti.php?idGruppo=" + idGruppo;
            let response = await fetch(url);
            let txt = await response.text();
            console.log(txt);
            let data = JSON.parse(txt);
            console.log(data);
            
            let divButton = document.getElementById("divButton");
            divButton.innerHTML = "";
            
            if (data.status == "ERR") {
                divButton.innerHTML = `<div class="alert alert-warning m-2">${data.msg}</div>`;
                return;
            }
            
            if (data.data.length === 0) {
                divButton.innerHTML = `<div class="alert alert-info m-2">No users available to invite</div>`;
                
                let backButton = document.createElement("button");
                backButton.className = "btn btn-sm ms-2";
                backButton.innerHTML = '<i class="fas fa-arrow-left me-1"></i> Back';
                backButton.addEventListener("click", function() {
                    stampaBottonInviti(idGruppo);
                });
                divButton.appendChild(backButton);
                return;
            }
            
            let formGroup = document.createElement("div");
            formGroup.className = "d-flex gap-2 p-2 align-items-center";
            
            let select = document.createElement("select");
            select.className = "form-select";
            
            for (const utente of data.data) {
                let option = document.createElement("option");
                option.value = utente.username;
                option.innerHTML = utente.username;
                select.appendChild(option);
            }
            
            let button = document.createElement("button");
            button.className = "btn";
            button.innerHTML = '<i class="fas fa-user-plus me-1"></i> Invite';
            button.addEventListener("click", function () {
                invitaUtente(idGruppo, select.value);
            });
            
            let backButton = document.createElement("button");
            backButton.className = "btn btn-sm";
            backButton.innerHTML = '<i class="fas fa-arrow-left"></i>';
            backButton.addEventListener("click", function() {
                stampaBottonInviti(idGruppo);
            });
            
            formGroup.appendChild(backButton);
            formGroup.appendChild(select);
            formGroup.appendChild(button);
            divButton.appendChild(formGroup);
        }

        function stampaBottonInviti(idGruppo) {
            let divButton = document.getElementById("divButton");
            divButton.innerHTML = "";
            
            let groupActions = document.createElement("div");
            groupActions.className = "d-flex justify-content-between align-items-center p-2 w-100";
            
            let groupTitle = document.createElement("div");
            let currentGroup = document.querySelector(`.divGruppo.active h2`);
            if (currentGroup) {
                groupTitle.innerHTML = `<h5 class="m-0">${currentGroup.textContent}</h5>`;
            }
            
            let inviteButton = document.createElement("button");
            inviteButton.className = "btn";
            inviteButton.innerHTML = '<i class="fas fa-user-plus me-1"></i> Invite Users';
            inviteButton.addEventListener("click", function () {
                selezionaUtentiDaInvitare(idGruppo);
            });
            
            groupActions.appendChild(groupTitle);
            groupActions.appendChild(inviteButton);
            divButton.appendChild(groupActions);
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
                alert("User invited successfully!");
                stampaBottonInviti(idGruppo);
            }
            else {
                alert(data.msg);
            }
        }

        function creaInterfacciaCreazioneGruppo() {
            let divButton = document.getElementById("divButtonCreaGruppo");
            divButton.innerHTML = "";
            
            let formGroup = document.createElement("div");
            formGroup.className = "d-flex flex-column gap-2";
            
            let input = document.createElement("input");
            input.type = "text";
            input.id = "inputNomeGruppo";
            input.className = "form-control";
            input.placeholder = "Group name";
            
            let buttonGroup = document.createElement("div");
            buttonGroup.className = "d-flex gap-2";
            
            let createButton = document.createElement("button");
            createButton.className = "btn flex-grow-1";
            createButton.innerHTML = '<i class="fas fa-check me-1"></i> Create';
            createButton.addEventListener("click", function () {
                creaGruppo();
            });
            
            let cancelButton = document.createElement("button");
            cancelButton.className = "btn btn-outline-secondary";
            cancelButton.innerHTML = '<i class="fas fa-times"></i>';
            cancelButton.addEventListener("click", function() {
                divButton.innerHTML = `<button class="btn" onclick="creaInterfacciaCreazioneGruppo()">
                    <i class="fas fa-plus-circle me-2"></i>Create Group
                </button>`;
            });
            
            buttonGroup.appendChild(createButton);
            buttonGroup.appendChild(cancelButton);
            
            formGroup.appendChild(input);
            formGroup.appendChild(buttonGroup);
            divButton.appendChild(formGroup);
            
            // Focus the input
            input.focus();
            
            // Add keypress event for Enter key
            input.addEventListener("keypress", function(event) {
                if (event.key === "Enter") {
                    creaGruppo();
                }
            });
        }

        async function creaGruppo() {
            let input = document.getElementById("inputNomeGruppo");
            let nomeGruppo = input.value.trim();
            
            if (nomeGruppo === "") {
                alert("Please enter a group name");
                return;
            }
            
            let url = "ajax/creaGruppo.php?nomeGruppo=" + encodeURIComponent(nomeGruppo);
            console.log(url);
            let response = await fetch(url);
            let txt = await response.text();
            console.log(txt);
            let data = JSON.parse(txt);
            console.log(data);
            if (data.status == "OK") {
                let divButtonCreaGruppo = document.getElementById("divButtonCreaGruppo");
                divButtonCreaGruppo.innerHTML = `<button class="btn" onclick="creaInterfacciaCreazioneGruppo()">
                    <i class="fas fa-plus-circle me-2"></i>Create Group
                </button>`;
                stampaGruppi();
            }
            else {
                alert(data.msg);
            }
        }
    </script>
</body>

</html>