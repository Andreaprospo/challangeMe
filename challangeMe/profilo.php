<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/styleProfilo.css">
    <title>Document</title>
</head>

<body>
    <div id="superDiv">
        <div id="divfoto">
            <div>
            <img src="" alt="Foto profilo" id="imgProfilo">
            </div>
        </div>
        <div id="divInformazioni">
            <div id="divUsername"></div>
            <div id="divDescrizione"></div>
        </div>
        <div id="divPunteggio">

        </div>
        <div id="divSfide">
            <div id="divSfideAccettate">
                <div>
                    <h2>Sfide accettate</h2>
                </div>
                <div id="sottoDivSfideAccettate"></div>
            </div>
            <div id="divSfideCompletate">
                <div>
                    <h2>Sfide completate</h2>
                </div>
                <div id="sottoDivSfideCompletate"></div>
            </div>
        </div>
        <div id="divModificaProfilo">
            <h2>Modifica profilo</h2>
            <button id="buttonModifica" onclick="modificaProfilo()">Modalità modifica</button>
        </div>
        <div id = "divLogout">
            <button><a href="logout.php">Logout</a></button>
        </div>
    </div>
    <?php
    require_once("footer.php");
    ?>
</body>

</html>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    stampaSfideAccettate();
    stampaSfideCompletate();
    stampaAllInfo();
});

async function stampaAllInfo() {
    let url = "ajax/getAllUsernameInfo.php";
    let response = await fetch(url);
    let txt = await response.text();
    let data = JSON.parse(txt);
    let divFoto = document.querySelector("#divfoto");
    let img = document.querySelector("#imgProfilo");
    if (data.data.pathFotoProfilo == null || data.data.pathFotoProfilo == "") {
        img.src = "Immagini/default.png";
    } else {
        img.src = data.data.pathFotoProfilo;
    }

    let divUsername = document.querySelector("#divUsername");
    divUsername.innerHTML = data.data.username;
    let divDescrizione = document.querySelector("#divDescrizione");
    divDescrizione.innerHTML = data.data.descrizione;
}

async function stampaSfideCompletate() {
    let url = "ajax/getAllSfideCompletate.php";
    let response = await fetch(url);
    let txt = await response.text();
    let data = JSON.parse(txt);
    let sottoDivSfideCompletate = document.querySelector("#sottoDivSfideCompletate");
    sottoDivSfideCompletate.innerHTML = "";
    if (data.status == "OK") {
        for (let i = 0; i < data.data.length; i++) {
            let div = document.createElement("div");
            div.appendChild(stampaSfidaCompletata(data.data[i]));
            sottoDivSfideCompletate.appendChild(div);
        }
    } else {
        let div = document.createElement("div");
        div.innerHTML = "Nessuna sfida completata";
        sottoDivSfideCompletate.appendChild(div);
    }
}

function stampaSfidaCompletata($data) {
    let div = document.createElement("div");
    div.innerHTML = $data["descrizione"] + " --- " + $data["data"] + " --- " + $data["ora"] + " --- " + $data["dataCompletamento"] + " --- " + $data["oraCompletamento"];
    return div;
}

async function stampaSfideAccettate() {
    let url = "ajax/getAllSfideAccettate.php";
    let response = await fetch(url);
    let txt = await response.text();
    let data = JSON.parse(txt);
    let sottoDivSfideAccettate = document.querySelector("#sottoDivSfideAccettate");
    sottoDivSfideAccettate.innerHTML = "";
    if (data.status == "OK") {
        for (let i = 0; i < data.data.length; i++) {
            let div = document.createElement("div");
            div.appendChild(stampaSfida(data.data[i]));
            sottoDivSfideAccettate.appendChild(div);
        }
    } else {
        let div = document.createElement("div");
        div.innerHTML = "Nessuna sfida accettata";
        sottoDivSfideAccettate.appendChild(div);
    }
}

function stampaSfida($data) {
    let div = document.createElement("div");
    div.innerHTML = $data["descrizione"] + " --- " + $data["dataInizio"] + " --- " + $data["oraInizio"];
    return div;
}

function modificaProfilo() {
    let divDescrizione = document.querySelector("#divDescrizione");
    let descrizioneAttuale = divDescrizione.innerHTML;
    divDescrizione.innerHTML = "";

    let formGroupDescrizione = document.createElement("div");
    formGroupDescrizione.className = "form-group";
    divDescrizione.appendChild(formGroupDescrizione);

    let labelDescrizione = document.createElement("label");
    labelDescrizione.className = "form-label";
    labelDescrizione.htmlFor = "inputDescrizione";
    labelDescrizione.textContent = "Descrizione profilo:";
    formGroupDescrizione.appendChild(labelDescrizione);

    let inputDescrizione = document.createElement("textarea");
    inputDescrizione.id = "inputDescrizione";
    inputDescrizione.value = descrizioneAttuale;
    inputDescrizione.rows = 3;
    formGroupDescrizione.appendChild(inputDescrizione);

    let divFoto = document.querySelector("#divfoto");
    let fileInputContainer = document.createElement("div");
    fileInputContainer.className = "file-input-container";
    divFoto.appendChild(fileInputContainer);

    let fileInputLabel = document.createElement("label");
    fileInputLabel.className = "file-input-label";
    fileInputLabel.htmlFor = "inputFoto";
    fileInputLabel.textContent = "Cambia foto profilo";
    fileInputContainer.appendChild(fileInputLabel);

    let inputFoto = document.createElement("input");
    inputFoto.type = "file";
    inputFoto.accept = "image/*";
    inputFoto.id = "inputFoto";
    fileInputContainer.appendChild(inputFoto);

    let fileNameDisplay = document.createElement("div");
    fileNameDisplay.className = "file-name-display";
    fileInputContainer.appendChild(fileNameDisplay);

    inputFoto.addEventListener("change", function () {
        if (this.files && this.files[0]) {
            fileNameDisplay.textContent = this.files[0].name;
        } else {
            fileNameDisplay.textContent = "";
        }
    });

    let divModificaProfilo = document.querySelector("#divModificaProfilo");
    let buttonModifica = document.querySelector("#buttonModifica");
    buttonModifica.style.display = "none";

    let buttonsContainer = document.createElement("div");
    buttonsContainer.className = "edit-buttons-container";
    divModificaProfilo.appendChild(buttonsContainer);

    let buttonSalva = document.createElement("button");
    buttonSalva.innerHTML = "Salva modifiche";
    buttonSalva.id = "buttonSalva";
    buttonSalva.addEventListener("click", function () {
        salvaModifiche();
    });
    buttonsContainer.appendChild(buttonSalva);

    let buttonAnnulla = document.createElement("button");
    buttonAnnulla.innerHTML = "Annulla";
    buttonAnnulla.id = "buttonAnnulla";
    buttonAnnulla.addEventListener("click", function () {
        annullaModifiche();
    });
    buttonsContainer.appendChild(buttonAnnulla);
}

function annullaModifiche() {
    let fileInputContainer = document.querySelector(".file-input-container");
    if (fileInputContainer) {
        fileInputContainer.remove();
    }

    let divDescrizione = document.querySelector("#divDescrizione");
    let formGroupDescrizione = divDescrizione.querySelector(".form-group");
    if (formGroupDescrizione) {
        formGroupDescrizione.remove();
    }

    stampaAllInfo();

    let buttonsContainer = document.querySelector(".edit-buttons-container");
    if (buttonsContainer) {
        buttonsContainer.remove();
    }

    let buttonModifica = document.querySelector("#buttonModifica");
    buttonModifica.style.display = "inline-block";
}

function salvaModifiche() {
    let inputFoto = document.querySelector("#inputFoto");
    let inputDescrizione = document.querySelector("#inputDescrizione");

    let formData = new FormData();
    if (inputFoto.files[0]) {
        formData.append("pathFoto", inputFoto.files[0]);
    }
    formData.append("descrizione", inputDescrizione.value);

    fetch("ajax/salvaModifiche.php", {
        method: "POST",
        body: formData
    })
        .then(response => {
            if (response.ok) {
                return response.text();
            } else {
                throw new Error("Errore durante il salvataggio delle modifiche.");
            }
        })
        .then(data => {
            let fileInputContainer = document.querySelector(".file-input-container");
            if (fileInputContainer) {
                fileInputContainer.remove();
            }

            let formGroupDescrizione = document.querySelector(".form-group");
            if (formGroupDescrizione) {
                formGroupDescrizione.remove();
            }

            stampaAllInfo();

            let buttonsContainer = document.querySelector(".edit-buttons-container");
            if (buttonsContainer) {
                buttonsContainer.remove();
            }

            let buttonModifica = document.querySelector("#buttonModifica");
            buttonModifica.style.display = "inline-block";
        })
        .catch(error => {
            console.error("Errore:", error);
            alert("Errore durante il salvataggio.");
        });
}

</script>