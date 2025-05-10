<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challenge App</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts - Nunito -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="CSS/styleHome.css">
</head>
<body>
    <div id="superDiv">
        <!-- Header con brand -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <i class="bi bi-trophy-fill me-2"></i>Challenge App
                </a>
                <span class="navbar-text text-white">
                    <i class="bi bi-person-circle me-1"></i>
                    <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'User'; ?>
                </span>
            </div>
        </nav>

        <!-- Top users ranking -->
        <div id="divTop">
            <!-- Top users will be populated here -->
        </div>

        <div id="divCorpo" class="container-fluid">
            <!-- Sfide accettate dall'utente -->
            <div id="divSfideAccettate">
                <h4 class="mb-3"><i class="bi bi-bookmark-star-fill me-2"></i>Le tue sfide attive</h4>
                <!-- Sfide accettate will be populated here -->
            </div>

            <!-- Sezione utenti -->
            <div id="divSuggeritiSeguiti">
                <h4 class="mb-3"><i class="bi bi-people-fill me-2"></i>Community</h4>
                <div id="divBottoni">
                    <button onclick="stampaSeguiti()" class="btn-primary">
                        <i class="bi bi-people-fill"></i> Utenti seguiti
                    </button>
                    <button onclick="stampaAllUtenti()" class="btn-info">
                        <i class="bi bi-person-plus-fill"></i> Utenti suggeriti
                    </button>
                </div>
                <div id="divUtenti">
                    <!-- Utenti will be populated here -->
                </div>
            </div>

            <!-- Sezione sfide -->
            <div id="divSfide">
                <h4 class="mb-3"><i class="bi bi-lightning-charge-fill me-2"></i>Sfide</h4>
                <div id="divBottoniSfide">
                    <button onclick="stampaSfideCompletate()">
                        <i class="bi bi-check-circle-fill"></i> Sfide completate
                    </button>
                    <button onclick="stampaNuoveSfide()">
                        <i class="bi bi-lightning-fill"></i> Nuove sfide
                    </button>
                    <button onclick="creaSfida()">
                        <i class="bi bi-plus-circle-fill"></i> Crea sfida
                    </button>
                </div>
                <div id="sottoDivSfide">
                    <!-- Sfide will be populated here -->
                </div>
            </div>
        </div>

        <!-- Logout button -->
        <div id="divLogout">
            <button><a href="logout.php">Logout</a></button>
        </div>

        <!-- Footer -->
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
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- App JavaScript -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            stampaSfideAccettate();
            stampaAllUtenti();
            creaClassifica();
        });

        async function stampaAllUtenti() {
            let url = "ajax/getAllUtentiNonSeguiti.php";
            let response = await fetch(url);
            let txt = await response.text();
            let data = JSON.parse(txt);
            let divUtenti = document.querySelector("#divUtenti");
            divUtenti.innerHTML = ""; // Pulisce il contenuto precedente
            
            if(data.status == "OK") {
                for(let i = 0; i < data.data.length; i++) {
                    let div = document.createElement("div");
                    let button = document.createElement("button");
                    button.innerHTML = '<i class="bi bi-person-plus"></i> Segui';
                    button.value = data.data[i].username;
                    button.addEventListener("click", function(event){
                            segui(event, true);
                    });
                    div.innerHTML += stampaUtente(data.data[i]);
                    div.appendChild(button);
                    divUtenti.appendChild(div);
                    
                    // Add animation
                    setTimeout(() => {
                        div.classList.add("fade-in");
                    }, i * 100);
                }   
            } else {
                let div = document.createElement("div");
                div.innerHTML = '<div class="alert alert-info" role="alert"><i class="bi bi-info-circle"></i> Nessun utente trovato</div>';
                divUtenti.appendChild(div);
            }
        }

        async function stampaSeguiti() {
            let url = "ajax/getAllSeguiti.php";
            let response = await fetch(url);
            let txt = await response.text();
            let data = JSON.parse(txt);
            let divUtenti = document.querySelector("#divUtenti");
            divUtenti.innerHTML = ""; // Pulisce il contenuto precedente
            
            if(data.status == "OK") {
                for(let i = 0; i < data.data.length; i++) {
                    let div = document.createElement("div");
                    let button = document.createElement("button");
                    button.innerHTML = '<i class="bi bi-person-dash"></i> Smetti di seguire';
                    button.value = data.data[i].username;
                    button.addEventListener("click", function(event){
                            segui(event, false);
                    });
                    div.innerHTML += stampaUtente(data.data[i]);
                    div.appendChild(button);
                    divUtenti.appendChild(div);
                    
                    // Add animation
                    setTimeout(() => {
                        div.classList.add("fade-in");
                    }, i * 100);
                }   
            } else {
                let div = document.createElement("div");
                div.innerHTML = '<div class="alert alert-info" role="alert"><i class="bi bi-info-circle"></i> Nessun seguito trovato</div>';
                divUtenti.appendChild(div);
            }
        }

        function stampaUtente($data) {
            return `<div class="user-info">
                        <i class="bi bi-person-circle me-2"></i>
                        <span>${$data["username"]}</span>
                    </div>`;
        }

        async function segui(event, azione) {
            //se true allora segui, altriemnti smetti di seguire
            let url = "ajax/segui.php?username=" + event.target.value + "&azione=" + azione;
            let response = await fetch(url);
            let txt = await response.text();
            let data = JSON.parse(txt);
            
            if(azione) {
                stampaAllUtenti(); // Ricarica gli utenti suggeriti
                showToast(`Ora segui ${event.target.value}`);
            } else {
                stampaSeguiti(); // Ricarica gli utenti seguiti
                showToast(`Hai smesso di seguire ${event.target.value}`);
            }
        }

        // Toast notification instead of alert
        function showToast(message) {
            // Create toast container if it doesn't exist
            let toastContainer = document.getElementById('toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
                document.body.appendChild(toastContainer);
            }
            
            // Create toast
            const toastId = 'toast-' + Date.now();
            const toast = document.createElement('div');
            toast.className = 'toast show';
            toast.id = toastId;
            toast.innerHTML = `
                <div class="toast-header">
                    <i class="bi bi-info-circle me-2 text-primary"></i>
                    <strong class="me-auto">Challenge App</strong>
                    <button type="button" class="btn-close" onclick="document.getElementById('${toastId}').remove()"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            `;
            
            toastContainer.appendChild(toast);
            
            // Remove toast after 3 seconds
            setTimeout(() => {
                const oldToast = document.getElementById(toastId);
                if (oldToast) oldToast.remove();
            }, 3000);
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
            let response = await fetch(url);
            let txt = await response.text();
            let data = JSON.parse(txt);
            let divSfideAccettate = document.querySelector("#sottoDivSfide");
            divSfideAccettate.innerHTML = ""; // Pulisce il contenuto precedente
            
            if(data.status == "OK") {
                for(let i = 0; i < data.data.length; i++) {
                    let div = document.createElement("div");
                    let button = document.createElement("button");
                    button.innerHTML = '<i class="bi bi-trophy"></i> Accetta sfida';
                    button.value = data.data[i].id;
                    button.addEventListener("click", function(event){
                            accettaSfida(event);
                    });
                    div.appendChild(stampaSfida(data.data[i]));
                    div.appendChild(button);
                    divSfideAccettate.appendChild(div);
                    
                    // Add animation
                    setTimeout(() => {
                        div.classList.add("fade-in");
                    }, i * 100);
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
            let data = JSON.parse(txt);
            
            stampaNuoveSfide(); // Ricarica le nuove sfide
            stampaSfideAccettate();
            showToast("Sfida accettata con successo!");
        }

        function stampaSfidaAccettata($data) {
            let div = document.createElement("div");
            let buttonFinish = document.createElement("button");
            buttonFinish.innerHTML = '<i class="bi bi-check2-all"></i> Completata';
            buttonFinish.value = $data["id"];
            buttonFinish.addEventListener("click", function(event){
                completaSfida(event);
            });

            let buttonElimina = document.createElement("button");
            buttonElimina.innerHTML = '<i class="bi bi-trash"></i> Elimina';
            buttonElimina.value = $data["id"];
            buttonElimina.addEventListener("click", function(event){
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
            let response = await fetch(url);
            let txt = await response.text();
            let data = JSON.parse(txt);
            let divSfideAccettate = document.querySelector("#divSfideAccettate");
            
            // Mantieni il titolo
            let title = divSfideAccettate.querySelector("h4") || document.createElement("h4");
            title.className = "mb-3";
            title.innerHTML = '<i class="bi bi-bookmark-star-fill me-2"></i>Le tue sfide attive';
            
            divSfideAccettate.innerHTML = ""; // Pulisce il contenuto precedente
            divSfideAccettate.appendChild(title);
            
            if(data.status == "OK") {
                for(let i = 0; i < data.data.length; i++) {
                    let div = document.createElement("div");                
                    div.appendChild(stampaSfidaAccettata(data.data[i]));
                    divSfideAccettate.appendChild(div);
                    
                    // Add animation
                    setTimeout(() => {
                        div.classList.add("fade-in");
                    }, i * 100);
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
            let data = JSON.parse(txt);
            
            stampaSfideAccettate(); // Ricarica le sfide
            showToast("Hai completato la sfida! +10 punti");
        }

        async function eliminaSfida(event) {
            if (!confirm("Sei sicuro di voler eliminare questa sfida?")) return;
            
            let idSfida = event.target.value;
            let url = "ajax/eliminaSfida.php?idSfida=" + idSfida;
            let response = await fetch(url);
            let txt = await response.text();
            let data = JSON.parse(txt);
            
            stampaSfideAccettate(); // Ricarica le sfide
            stampaNuoveSfide(); // Ricarica le nuove sfide
            showToast("Sfida eliminata");
        }

        async function stampaSfideCompletate() {
            let url = "ajax/getAllSfideCompletate.php";
            let response = await fetch(url);
            let txt = await response.text();
            let data = JSON.parse(txt);
            let sottoDivSfide = document.querySelector("#sottoDivSfide");
            sottoDivSfide.innerHTML = ""; // Pulisce il contenuto precedente
            
            if(data.status == "OK") {
                for(let i = 0; i < data.data.length; i++) {
                    let div = document.createElement("div");                
                    div.appendChild(stampaSfidaCompletata(data.data[i]));
                    sottoDivSfide.appendChild(div);
                    
                    // Add animation
                    setTimeout(() => {
                        div.classList.add("fade-in");
                    }, i * 100);
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

        async function creaSfida() {
            // Create modal for challenge creation
            const modalId = 'createChallengeModal';
            let modal = document.getElementById(modalId);
            
            if (!modal) {
                modal = document.createElement('div');
                modal.id = modalId;
                modal.className = 'modal fade';
                modal.setAttribute('tabindex', '-1');
                modal.setAttribute('aria-labelledby', 'createChallengeModalLabel');
                modal.setAttribute('aria-hidden', 'true');
                
                modal.innerHTML = `
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="createChallengeModalLabel">Crea nuova sfida</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="challengeForm">
                                    <div class="mb-3">
                                        <label for="descrizione" class="form-label">Descrizione</label>
                                        <input type="text" class="form-control" id="descrizione" required>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="dataInizio" class="form-label">Data inizio</label>
                                            <input type="date" class="form-control" id="dataInizio" required>
                                        </div>
                                        <div class="col">
                                            <label for="oraInizio" class="form-label">Ora inizio</label>
                                            <input type="time" class="form-control" id="oraInizio" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="dataFine" class="form-label">Data fine</label>
                                            <input type="date" class="form-control" id="dataFine" required>
                                        </div>
                                        <div class="col">
                                            <label for="oraFine" class="form-label">Ora fine</label>
                                            <input type="time" class="form-control" id="oraFine" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="pathFoto" class="form-label">URL immagine (opzionale)</label>
                                        <input type="text" class="form-control" id="pathFoto">
                                    </div>
                                    <div class="mb-3">
                                        <label for="punteggio" class="form-label">Punteggio</label>
                                        <input type="number" class="form-control" id="punteggio" min="1" value="10" required>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                                <button type="button" class="btn btn-primary" id="submitChallenge">Crea sfida</button>
                            </div>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(modal);
                
                // Add event listener to form submission
                document.getElementById('submitChallenge').addEventListener('click', async () => {
                    const form = document.getElementById('challengeForm');
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }
                    
                    const descrizione = document.getElementById('descrizione').value;
                    const dataInizio = document.getElementById('dataInizio').value;
                    const oraInizio = document.getElementById('oraInizio').value;
                    const dataFine = document.getElementById('dataFine').value;
                    const oraFine = document.getElementById('oraFine').value;
                    const pathFoto = document.getElementById('pathFoto').value || '';
                    const punteggio = document.getElementById('punteggio').value;
                    
                    await submitCreaSfida(descrizione, dataInizio, oraInizio, dataFine, oraFine, pathFoto, punteggio);
                    
                    // Close the modal
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    modalInstance.hide();
                });
            }
            
            // Show the modal
            const modalInstance = new bootstrap.Modal(modal);
            modalInstance.show();
        }

        async function submitCreaSfida(descrizione, dataInizio, oraInizio, dataFine, oraFine, pathFoto, punteggio) {
            let url = `ajax/creaSfida.php?descrizione=${encodeURIComponent(descrizione)}&dataInizio=${dataInizio}&oraInizio=${oraInizio}&dataFine=${dataFine}&oraFine=${oraFine}&pathFoto=${encodeURIComponent(pathFoto)}&punteggio=${punteggio}`;
            
            let response = await fetch(url);
            let txt = await response.text();
            let data = JSON.parse(txt);
            
            if(data.status == "OK") {
                showToast("Sfida creata con successo!");
                stampaNuoveSfide(); // Ricarica le nuove sfide
            } else {
                showToast("Errore nella creazione della sfida: " + data.msg, "error");
            }
        }

        async function creaClassifica() {
            let url = "ajax/getUtentiPunteggioMaggiore.php?numeroUtenti=10";
            let response = await fetch(url);
            let txt = await response.text();
            let data = JSON.parse(txt);
            
            let divTop = document.querySelector("#divTop");
            divTop.innerHTML = '<h5 class="leaderboard-title"><i class="bi bi-trophy"></i> Classifica</h5>'; // Add title
            
            if(data.status == "ERR") {
                console.log(data.msg);
                return;
            }

            for (let i = 0; i < data.data.length; i++) {
                const utente = data.data[i];
                let div = document.createElement("div");
                div.className = "user-item";
                div.setAttribute("data-position", i + 1);
                
                let sottoDiv = document.createElement("div");
                let img = document.createElement("img");
                img.src = utente.pathFotoProfilo || 'images/default-avatar.jpg'; // Default image fallback
                img.className = "icona";
                img.alt = "Foto profilo";
                img.onerror = function() {
                    this.src = 'images/default-avatar.jpg';
                };

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
        
        // Function to animate top users
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
        
        // Function to move users horizontally (swap positions)
        function moveTopUsers(users) {
            if (users.length < 2) return;
            
            // Select two random users to swap
            const userCount = users.length;
            const index1 = Math.floor(Math.random() * userCount);
            let index2 = Math.floor(Math.random() * userCount);
            
            // Make sure they're different
            while(index2 === index1) {
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
        
        // Function to apply random animations to users
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
        
        // Function to shuffle array (Fisher-Yates algorithm)
        function shuffleArray(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
            return array;
        }
    </script>
</body>
</html>