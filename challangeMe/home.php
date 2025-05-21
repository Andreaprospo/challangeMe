<?php
    require_once("Classi/Utente.php");
    require_once("Classi/GestoreDB.php");

    if (!isset($_SESSION)) {
        session_start();
    }

    if (!isset($_SESSION["utenteCorrente"])) {
        header("Location: login.php");
        exit();
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/styleHome.css">
    <link rel="icon" type="image/png" href="icona.png">
    <title>Home</title>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div id="superDiv">
        <div id="divTop">
            <!-- Top users will be populated here -->
        </div>
        <div id="divCorpo" class="container-fluid">
            <div id = "divCitazione"></div>
            <div id="divSfideAccettate"></div>
            <div id="divSuggeritiSeguiti">
                <div id="divBottoni">
                    <button onclick="stampaSeguiti()"><i class="bi bi-people-fill"></i> Utenti seguiti</button>
                    <button onclick="stampaAllUtenti()"><i class="bi bi-person-plus-fill"></i> Utenti suggeriti</button>
                </div>
                <div id="divUtenti"></div>
            </div>
            <div id="divSfide">
                <div id="divBottoniSfide">
                    <button onclick="stampaSfideCompletate()"><i class="bi bi-check-circle-fill"></i> Sfide
                        completate</button>
                    <button onclick="stampaNuoveSfide()"><i class="bi bi-lightning-fill"></i> Nuove sfide</button>
                    <button onclick="mostraModalCreaSfida()"><i class="bi bi-plus-circle-fill"></i> Crea sfida</button>
                </div>
                <div id="sottoDivSfide"></div>
            </div>
        </div>
        <div id="divLogout">
            <button><a href="logout.php">Logout</a></button>
        </div>
    </div>



    <!-- Modal per creare una nuova sfida -->
    <div class="modal fade" id="creaSfidaModal" tabindex="-1" aria-labelledby="creaSfidaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="creaSfidaModalLabel">Crea una nuova sfida</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="creaSfidaForm">
                        <div class="mb-3">
                            <label for="descrizione" class="form-label">Descrizione</label>
                            <input type="text" class="form-control" id="descrizione" required>
                        </div>
                        <div class="mb-3">
                            <label for="dataInizio" class="form-label">Data inizio</label>
                            <input type="date" class="form-control" id="dataInizio" required>
                        </div>
                        <div class="mb-3">
                            <label for="oraInizio" class="form-label">Ora inizio</label>
                            <input type="time" class="form-control" id="oraInizio" required>
                        </div>
                        <div class="mb-3">
                            <label for="dataFine" class="form-label">Data fine</label>
                            <input type="date" class="form-control" id="dataFine" required>
                        </div>
                        <div class="mb-3">
                            <label for="oraFine" class="form-label">Ora fine</label>
                            <input type="time" class="form-control" id="oraFine" required>
                        </div>
                        <div class="mb-3">
                            <label for="punteggio" class="form-label">Punteggio</label>
                            <input type="number" class="form-control" id="punteggio" min="1" required>
                        </div>
                        <div class="mb-3">
                            <div class="form-text">Un'immagine verrà cercata automaticamente in base alla descrizione</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="button" class="btn btn-primary" id="btnSalvaSfida">Crea sfida</button>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <div class="footer-title">Challenge App</div>
                <p>Una piattaforma per sfide e competizioni</p>
            </div>
            <div class="footer-section">
                <div class="footer-title">Menu</div>
                <?php
                require_once("footer.php");
                ?>
            </div>
            <div class="footer-section">
                <div class="footer-title">© 2025 Challenge App</div>
                <div>Tutti i diritti riservati</div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            stampaSfideAccettate();
            stampaAllUtenti();
            creaClassifica();
            stampaCitazioneDelGiorno();
            // Aggiungi event listener per il pulsante di salvataggio nel modale
            document.getElementById("btnSalvaSfida").addEventListener("click", inviaFormSfida);
        });

        // Funzione per navigare alla pagina del profilo utente
        function navigaAProfiloUtente(event) {
            const username = event.currentTarget.dataset.username;
            if (username) {
                window.location.href = `profilo.php?username=${encodeURIComponent(username)}`;
            }
        }

        async function stampaAllUtenti() {
            let url = "ajax/getAllUtentiNonSeguiti.php";
            console.log(url);
            let response = await fetch(url);
            let txt = await response.text();
            console.log(txt);
            let data = JSON.parse(txt);
            let divUtenti = document.querySelector("#divUtenti");
            divUtenti.innerHTML = ""; // Pulisce il contenuto precedente
            let inputRicerca = document.createElement("input");
            inputRicerca.type = "text";
            inputRicerca.placeholder = "Cerca tra gli utenti seguiti...";
            inputRicerca.className = "form-control mb-3";
            inputRicerca.addEventListener("input", function () {
                let filter = inputRicerca.value.toLowerCase();
                let utenti = divUtenti.querySelectorAll(".user-container");
                for (const utente of utenti) {
                    let username = utente.querySelector(".user-info span").textContent.toLowerCase();
                    if (username.includes(filter)) {
                        utente.style.display = "";
                    } else {
                        utente.style.display = "none";
                    }
                }
            });
            divUtenti.appendChild(inputRicerca);
            if (data.status == "OK") {
                for (let i = 0; i < data.data.length; i++) {
                    let div = document.createElement("div");
                    div.className = "user-container";
                    
                    // Rendi la parte dell'utente cliccabile
                    let userDiv = document.createElement("div");
                    userDiv.className = "user-info clickable";
                    userDiv.innerHTML = `<span>${data.data[i].username}</span>`;
                    userDiv.dataset.username = data.data[i].username;
                    userDiv.addEventListener("click", navigaAProfiloUtente);
                    
                    let button = document.createElement("button");
                    button.innerHTML = '<i class="bi bi-person-plus"></i> Segui';
                    button.value = data.data[i].username;
                    button.addEventListener("click", function (event) {
                        event.stopPropagation(); // Impedisce che il click sul pulsante attivi anche la navigazione
                        segui(event, true);
                    });
                    
                    div.appendChild(userDiv);
                    div.appendChild(button);
                    divUtenti.appendChild(div);
                }
            } else {
                let div = document.createElement("div");
                div.innerHTML = '<div class="alert alert-info" role="alert"><i class="bi bi-info-circle"></i> Nessun utente trovato</div>';
                divUtenti.appendChild(div);
            }
        }

        async function stampaSeguiti() {
            let url = "ajax/getAllSeguiti.php";
            console.log(url);
            let response = await fetch(url);
            let txt = await response.text();
            console.log(txt);
            let data = JSON.parse(txt);
            let divUtenti = document.querySelector("#divUtenti");
            divUtenti.innerHTML = ""; // Pulisce il contenuto precedente
            let inputRicerca = document.createElement("input");
            inputRicerca.type = "text";
            inputRicerca.placeholder = "Cerca tra gli utenti seguiti...";
            inputRicerca.className = "form-control mb-3";
            inputRicerca.addEventListener("input", function () {
                let filter = inputRicerca.value.toLowerCase();
                let utenti = divUtenti.querySelectorAll(".user-container");
                for (const utente of utenti) {
                    let username = utente.querySelector(".user-info span").textContent.toLowerCase();
                    if (username.includes(filter)) {
                        utente.style.display = "";
                    } else {
                        utente.style.display = "none";
                    }
                }
            });
            divUtenti.appendChild(inputRicerca);

            if (data.status == "OK") {
                for (let i = 0; i < data.data.length; i++) {
                    let div = document.createElement("div");
                    div.className = "user-container";
                    
                    // Rendi la parte dell'utente cliccabile
                    let userDiv = document.createElement("div");
                    userDiv.className = "user-info clickable";
                    userDiv.innerHTML = `<span>${data.data[i].username}</span>`;
                    userDiv.dataset.username = data.data[i].username;
                    userDiv.addEventListener("click", navigaAProfiloUtente);
                    
                    let button = document.createElement("button");
                    button.innerHTML = '<i class="bi bi-person-dash"></i> Smetti di seguire';
                    button.value = data.data[i].username;
                    button.addEventListener("click", function (event) {
                        event.stopPropagation(); // Impedisce che il click sul pulsante attivi anche la navigazione
                        segui(event, false);
                    });
                    
                    div.appendChild(userDiv);
                    div.appendChild(button);
                    divUtenti.appendChild(div);
                }
            } else {
                let div = document.createElement("div");
                div.innerHTML = '<div class="alert alert-info" role="alert"><i class="bi bi-info-circle"></i> Nessun seguito trovato</div>';
                divUtenti.appendChild(div);
            }
        }

        function stampaUtente($data) {
            return "<span>" + $data["username"] + "</span>";
        }

        async function segui(event, azione) {
            //se true allora segui, altriemnti smetti di seguire
            let url = "ajax/segui.php?username=" + event.target.value + "&azione=" + azione;
            console.log(url);
            let response = await fetch(url);
            let txt = await response.text();
            console.log(txt);
            let data = JSON.parse(txt);
            console.log(txt);
            if (azione) {
                stampaAllUtenti(); // Ricarica gli utenti suggeriti
                alert("Segui " + event.target.value);
            } else {
                stampaSeguiti(); // Ricarica gli utenti seguiti
                alert("Smetti di seguire " + event.target.value);
            }
        }

        function stampaSfida($data) {
            let div = document.createElement("div");
            div.innerHTML = `
                    <div class="challenge-card">
                        <div class="challenge-title">${$data["descrizione"]}</div>
                        <div class="challenge-dates">
                            <i class="bi bi-calendar-event"></i> ${$data["dataInizio"]}
                            <i class="bi bi-clock ms-2"></i> ${$data["oraInizio"]}
                        </div>
                    </div>
                `;
            return div;
        }

        async function stampaNuoveSfide() {
            let url = "ajax/getAllNuoveSfide.php";
            console.log(url);
            let response = await fetch(url);
            let txt = await response.text();
            console.log(txt);
            let data = JSON.parse(txt);
            let divSfideAccettate = document.querySelector("#sottoDivSfide");
            divSfideAccettate.innerHTML = ""; // Pulisce il contenuto precedente
            if (data.status == "OK") {
                for (let i = 0; i < data.data.length; i++) {
                    let div = document.createElement("div");
                    let button = document.createElement("button");
                    button.innerHTML = '<i class="bi bi-trophy"></i> Accetta sfida';
                    button.value = data.data[i].id;
                    button.addEventListener("click", function (event) {
                        accettaSfida(event);
                    });
                    div.appendChild(stampaSfida(data.data[i]));
                    div.appendChild(button);
                    divSfideAccettate.appendChild(div);
                }
            } else {
                let div = document.createElement("div");
                div.innerHTML = '<div class="alert alert-info" role="alert"><i class="bi bi-info-circle"></i> Nessuna sfida disponibile</div>';
                divSfideAccettate.appendChild(div);
            }
        }

        async function accettaSfida(event) {
            let idSfida = event.target.value;
            let url = "ajax/accettaSfida.php?idSfida=" + idSfida;
            let response = await fetch(url);
            let txt = await response.text();
            console.log(txt);
            let data = JSON.parse(txt);
            console.log(txt);
            stampaNuoveSfide(); // Ricarica le nuove sfide
            stampaSfideAccettate();
        }

        function stampaSfidaAccettata($data) {
            let div = document.createElement("div");
            let buttonFinish = document.createElement("button");
            buttonFinish.innerHTML = '<i class="bi bi-check2-all"></i> Completata';
            buttonFinish.value = $data["id"];
            buttonFinish.addEventListener("click", function (event) {
                completaSfida(event);
            });
            let dataInizio = $data["dataInizio"];
            let oraInizio = $data["oraInizio"];
            const timestampNow = Date.now();

            const dataCompleta = `${dataInizio} ${oraInizio}:00`; // formato ISO: YYYY-MM-DDTHH:mm:ss
            const timestampSfida = new Date(dataCompleta).getTime();
            if (timestampNow < timestampSfida) {
                buttonFinish.disabled = true;
            }

            let buttonElimina = document.createElement("button");
            buttonElimina.innerHTML = '<i class="bi bi-trash"></i> Elimina';
            buttonElimina.value = $data["id"];
            buttonElimina.addEventListener("click", function (event) {
                eliminaSfida(event);
            });

            div.innerHTML = `
                    <div class="challenge-card">
                        <div class="challenge-title">${$data["descrizione"]}</div>
                        <div class="challenge-dates">
                            <i class="bi bi-calendar-check"></i> ${$data["dataFine"]}
                            <i class="bi bi-clock ms-2"></i> ${$data["oraFine"]}
                        </div>
                    </div>
                `;
            div.appendChild(buttonFinish);
            div.appendChild(buttonElimina);
            return div;
        }

        async function stampaSfideAccettate() {
            let url = "ajax/getAllSfideAccettate.php";
            console.log(url);
            let response = await fetch(url);
            let txt = await response.text();
            console.log(txt);
            let data = JSON.parse(txt);
            let divSfideAccettate = document.querySelector("#divSfideAccettate");
            divSfideAccettate.innerHTML = ""; // Pulisce il contenuto precedente
            if (data.status == "OK") {
                for (let i = 0; i < data.data.length; i++) {
                    let div = document.createElement("div");
                    div.appendChild(stampaSfidaAccettata(data.data[i]));
                    divSfideAccettate.appendChild(div);
                }
            } else {
                let div = document.createElement("div");
                div.innerHTML = '<div class="alert alert-info" role="alert"><i class="bi bi-info-circle"></i> Nessuna sfida accettata</div>';
                divSfideAccettate.appendChild(div);
            }
        }

        async function completaSfida(event) {
            let idSfida = event.target.value;
            let url = "ajax/completaSfida.php?idSfida=" + idSfida;
            let response = await fetch(url);
            let txt = await response.text();
            console.log(txt);
            let data = JSON.parse(txt);
            console.log(txt);
            stampaSfideAccettate(); 
            creaClassifica();// Ricarica le nuove sfide
        }

        async function eliminaSfida(event) {
            let idSfida = event.target.value;
            let url = "ajax/eliminaSfida.php?idSfida=" + idSfida;
            console.log(url);
            let response = await fetch(url);
            let txt = await response.text();
            console.log(txt);
            let data = JSON.parse(txt);
            stampaSfideAccettate(); // Ricarica le nuove sfide
            stampaNuoveSfide(); // Ricarica le nuove sfide
        }

        async function stampaSfideCompletate() {
            let url = "ajax/getAllSfideCompletate.php";
            console.log(url);
            let response = await fetch(url);
            let txt = await response.text();
            console.log(txt);
            let data = JSON.parse(txt);
            let sottoDivSfide = document.querySelector("#sottoDivSfide");
            sottoDivSfide.innerHTML = ""; // Pulisce il contenuto precedente
            if (data.status == "OK") {
                for (let i = 0; i < data.data.length; i++) {
                    let div = document.createElement("div");
                    div.appendChild(stampaSfidaCompletata(data.data[i]));
                    sottoDivSfide.appendChild(div);
                }
            } else {
                let div = document.createElement("div");
                div.innerHTML = '<div class="alert alert-info" role="alert"><i class="bi bi-info-circle"></i> Nessuna sfida completata</div>';
                sottoDivSfide.appendChild(div);
            }
        }

        function stampaSfidaCompletata($data) {
            let div = document.createElement("div");
            div.innerHTML = `
                    <div class="challenge-card completed">
                        <div class="challenge-title">${$data["descrizione"]}</div>
                        <div class="challenge-dates">
                            <i class="bi bi-calendar-event"></i> ${$data["data"]}
                            <i class="bi bi-clock ms-2"></i> ${$data["ora"]}
                        </div>
                        <div class="challenge-completion">
                            <i class="bi bi-check-circle-fill text-success"></i> Completata il ${$data["dataCompletamento"]} alle ${$data["oraCompletamento"]}
                        </div>
                    </div>
                `;
            return div;
        }

        function mostraModalCreaSfida() {
            // Imposta le date di default (oggi e domani)
            const oggi = new Date();
            const domani = new Date();
            domani.setDate(domani.getDate() + 1);
            
            document.getElementById('dataInizio').value = oggi.toISOString().slice(0, 10);
            document.getElementById('dataFine').value = domani.toISOString().slice(0, 10);
            
            // Mostra il modale
            const modal = new bootstrap.Modal(document.getElementById('creaSfidaModal'));
            modal.show();
        }

        async function inviaFormSfida() {
            // Ottieni i valori dal form
            const descrizione = document.getElementById('descrizione').value;
            const dataInizio = document.getElementById('dataInizio').value;
            const oraInizio = document.getElementById('oraInizio').value;
            const dataFine = document.getElementById('dataFine').value;
            const oraFine = document.getElementById('oraFine').value;
            const punteggio = document.getElementById('punteggio').value;
            
            // Validazione base
            if (!descrizione || !dataInizio || !oraInizio || !dataFine || !oraFine || !punteggio) {
                alert("Tutti i campi sono obbligatori");
                return;
            }
            
            // Cerca l'immagine su Unsplash
            const pathFoto = await cercaImmagineUnsplash(descrizione);
            if (!pathFoto) {
                alert("Nessuna immagine trovata per la descrizione fornita.");
                return;
            }
            
            // Crea la sfida con AJAX
            let url = "ajax/creaSfida.php?descrizione=" + encodeURIComponent(descrizione) + 
                      "&dataInizio=" + encodeURIComponent(dataInizio) + 
                      "&oraInizio=" + encodeURIComponent(oraInizio) + 
                      "&dataFine=" + encodeURIComponent(dataFine) + 
                      "&oraFine=" + encodeURIComponent(oraFine) + 
                      "&pathFoto=" + encodeURIComponent(pathFoto) + 
                      "&punteggio=" + encodeURIComponent(punteggio);
            
            console.log(url);
            let response = await fetch(url);
            let txt = await response.text();
            console.log(txt);
            let data = JSON.parse(txt);
            
            // Chiudi il modale
            const modalElement = document.getElementById('creaSfidaModal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            modal.hide();
            
            // Gestisci la risposta
            if (data.status == "OK") {
                alert("Sfida creata con successo");
                stampaNuoveSfide(); // Ricarica le nuove sfide
            } else {
                alert("Errore nella creazione della sfida: " + data.msg);
            }
        }

        async function creaClassifica() {
            let url = "ajax/getUtentiPunteggioMaggiore.php?numeroUtenti=10";
            console.log(url);
            let response = await fetch(url);
            let txt = await response.text();
            console.log(txt);
            let data = JSON.parse(txt);

            let divTop = document.querySelector("#divTop");
            divTop.innerHTML = ""; // Clear previous content

            if (data.status == "ERR") {
                console.log(data.msg);
                return;
            }

            for (let i = 0; i < data.data.length; i++) {
                const utente = data.data[i];
                let div = document.createElement("div");
                div.className = "user-item clickable";
                div.setAttribute("data-position", i + 1);
                div.setAttribute("data-username", utente.username);
                div.addEventListener("click", navigaAProfiloUtente);

                let sottoDiv = document.createElement("div");
                let img = document.createElement("img");
                img.src = utente.pathFotoProfilo;
                img.className = "icona";
                img.alt = "Foto profilo";

                sottoDiv.innerHTML = `
                        <strong>${utente.username}</strong>
                        <div class="user-score">${utente.punteggio} pts</div>
                    `;
                div.appendChild(img);
                div.appendChild(sottoDiv);
                divTop.appendChild(div);

                // Apply entrance animation with delay based on position
                setTimeout(() => {
                    div.classList.add("user-slide-in");
                }, i * 100);
            }

            // Start the animation cycle for top users
            animateTopUsers();
        }

        function animateTopUsers() {
            const topUsers = document.querySelectorAll("#divTop .user-item");

            // Initial animation
            setTimeout(() => {
                startRandomAnimations(topUsers);
            }, 2000);

            // Set animation interval
            setInterval(() => {
                startRandomAnimations(topUsers);
            }, 5000);

            // Add horizontal movement animation
            setInterval(() => {
                moveTopUsers(topUsers);
            }, 12000);
        }

        function moveTopUsers(users) {
            if (users.length < 2) return;

            // Select two random users to swap
            const userCount = users.length;
            const index1 = Math.floor(Math.random() * userCount);
            let index2 = Math.floor(Math.random() * userCount);

            // Make sure they're different
            while (index2 === index1) {
                index2 = Math.floor(Math.random() * userCount);
            }

            const user1 = users[index1];
            const user2 = users[index2];

            // Get their positions
            const pos1 = user1.getAttribute("data-position");
            const pos2 = user2.getAttribute("data-position");

            // Add transition class
            user1.style.transition = "transform 1s ease";
            user2.style.transition = "transform 1s ease";

            // Calculate the distance to move
            const rect1 = user1.getBoundingClientRect();
            const rect2 = user2.getBoundingClientRect();
            const distance = rect2.left - rect1.left;

            // Animate the movement
            user1.style.transform = `translateX(${distance}px)`;
            user2.style.transform = `translateX(${-distance}px)`;

            // After animation, swap the actual DOM elements
            setTimeout(() => {
                // Reset transform
                user1.style.transform = "";
                user2.style.transform = "";

                // Update data-position
                user1.setAttribute("data-position", pos2);
                user2.setAttribute("data-position", pos1);

                // Move in the DOM
                const parent = user1.parentNode;
                const nextSibling = user2.nextSibling === user1 ? user2 : user2.nextSibling;

                parent.insertBefore(user2, user1);
                if (nextSibling) {
                    parent.insertBefore(user1, nextSibling);
                } else {
                    parent.appendChild(user1);
                }
            }, 1000);
        }

        function startRandomAnimations(users) {
            // Select random users to animate
            const userCount = users.length;
            const animateCount = Math.min(Math.floor(userCount / 3) + 1, 3);

            // Clear previous animations
            users.forEach(user => {
                user.classList.remove("user-bounce", "user-pulse");
            });

            // Animate random users
            const shuffledIndexes = shuffleArray([...Array(userCount).keys()]);

            for (let i = 0; i < animateCount; i++) {
                if (i < shuffledIndexes.length) {
                    const userIndex = shuffledIndexes[i];
                    const user = users[userIndex];

                    // Choose a random animation
                    const animationType = Math.random() > 0.5 ? "user-bounce" : "user-pulse";
                    user.classList.add(animationType);

                    // Remove animation class after it completes
                    const animationDuration = animationType === "user-bounce" ? 500 : 1500;
                    if (animationType === "user-bounce") {
                        setTimeout(() => {
                            user.classList.remove(animationType);
                        }, animationDuration);
                    }
                }
            }
        }

        function shuffleArray(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
            return array;
        }

        async function cercaImmagineUnsplash(query) {
            const accessKey = "OqzkH9tPNQVuaKyC9lZnrTq4Rpc4R9k8c9hPLSJ8gRw"; // ⚠️ Inserisci la tua chiave API
            const url = `https://api.unsplash.com/search/photos?query=${encodeURIComponent(query)}&per_page=1&client_id=${accessKey}`;

            try {
                const response = await fetch(url);
                const data = await response.json();

                if (data.results && data.results.length > 0) {
                    return data.results[0].urls.regular;
                } else {
                    return null;
                }
            } catch (error) {
                console.error("Errore nella richiesta API:", error);
                return null;
            }
        }

        async function stampaCitazioneDelGiorno()
        {
            const APIKEY = "4Ab078pQ9QO5FvqV+iWggA==ZlAynuVmqOC63Qht";
            let url = "https://api.api-ninjas.com/v1/quotes";
            try {
                const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-Api-Key': APIKEY, // sostituisci con la tua vera API Key
                },
                });

                if (!response.ok) {
                throw new Error(`Errore HTTP: ${response.status}`);
                }

                const data = await response.json();
                console.log(data); // oppure gestiscilo come preferisci
                let divCitazione = document.querySelector("#divCitazione");
                if (data != null) {
                    divCitazione.innerHTML = ""; // Pulisce il contenuto precedente
                    let div = document.createElement("div");
                    div.className = "citazione";
                    div.innerHTML = `<i class="bi bi-chat-left-quote"></i> ${data[0].quote} - ${data[0].author}`;
                    divCitazione.appendChild(div);
                } else {
                    let div = document.createElement("div");
                    div.innerHTML = '<div class="alert alert-info" role="alert"><i class="bi bi-info-circle"></i> Nessuna citazione trovata</div>';
                    divCitazione.appendChild(div);
                }
            } catch (error) {
                console.error('Errore durante la fetch:', error);
            }
        }
    </script>
</body>

</html>