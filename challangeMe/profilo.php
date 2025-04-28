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
        <div id ="divModificaProfilo">
            <h2>Modifica profilo</h2>
            <button id="buttonModifica" onclick="modificaProfilo()">Modalità modifica</button>
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
        getAllInfo();
    });

    async function getAllInfo() {
        let url = "ajax/getAllUsernameInfo.php";
        console.log(url);
        let response = await fetch(url);
        let txt = await response.text();
        console.log(txt);
        let data = JSON.parse(txt);
        let divFoto = document.querySelector("#divfoto");
        let img = document.createElement("img");
        if(data.data.pathFotoProfilo == null || data.data.pathFotoProfilo == "") {
            img.src = "Immagini/default.png"; // Percorso dell'immagine di default
        } else {
            img.src = data.data.pathFotoProfilo;
        }
        img.alt = "Foto profilo";
        divFoto.appendChild(img);
               
        let divUsername = document.querySelector("#divUsername");
        divUsername.innerHTML = data.data.username;
        let divDescrizione = document.querySelector("#divDescrizione");
        divDescrizione.innerHTML = data.data.descrizione;

    }

    async function stampaSfideCompletate()
    {
        let url = "ajax/getAllSfideCompletate.php";
        console.log(url);
        let response = await fetch(url);
        let txt = await response.text();
        console.log(txt);
        let data = JSON.parse(txt);
        let sottoDivSfideCompletate = document.querySelector("#sottoDivSfideCompletate");
        sottoDivSfideCompletate.innerHTML = ""; // Pulisce il contenuto precedente
        if(data.status == "OK")
        {
            for(let i = 0; i < data.data.length; i++)
            {
                let div = document.createElement("div");                
                div.appendChild(stampaSfidaCompletata(data.data[i]));
                sottoDivSfideCompletate.appendChild(div);
            }   
        }
        else
        {
            let div = document.createElement("div");
            div.innerHTML = "Nessuna sfida completata";
            sottoDivSfideCompletate.appendChild(div);
        }
    }

    function stampaSfidaCompletata($data)
    {
        let div = document.createElement("div");
        div.innerHTML = $data["descrizione"] + " --- " + $data["data"] + " --- " + $data["ora"] + " --- " + $data["dataCompletamento"] + " --- " + $data["oraCompletamento"];
        return div;
    }

    async function stampaSfideAccettate() {
        let url = "ajax/getAllSfideAccettate.php";
        console.log(url);
        let response = await fetch(url);
        let txt = await response.text();
        console.log(txt);
        let data = JSON.parse(txt);
        let sottoDivSfideAccettate = document.querySelector("#sottoDivSfideAccettate");
        sottoDivSfideAccettate.innerHTML = ""; // Pulisce il contenuto precedente
        if (data.status == "OK") {
            for (let i = 0; i < data.data.length; i++) {
                let div = document.createElement("div");
                div.appendChild(stampaSfida(data.data[i]));
                sottoDivSfideAccettate.appendChild(div);
            }
        }
        else {
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

    function modificaProfilo()
    {
        let divFoto = document.querySelector("#divfoto");
        let inputFoto = document.createElement("input");
        inputFoto.type = "file";
        inputFoto.accept = "image/*"; // Accetta solo file immagine
        inputFoto.id = "inputFoto";
        inputFoto.innerHTML = "Cambia foto profilo";
        divFoto.appendChild(inputFoto);
        let divDescrizione = document.querySelector("#divDescrizione");
        let inputDescrizione = document.createElement("input");
        inputDescrizione.id = "inputDescrizione";
        inputDescrizione.innerHTML = divDescrizione.innerHTML;
        divDescrizione.appendChild(inputDescrizione);
        let divModificaProfilo = document.querySelector("#divModificaProfilo");

        let buttonSalva = document.createElement("button");
        buttonSalva.innerHTML = "Salva";
        buttonSalva.id = "buttonSalva";
        buttonSalva.addEventListener("click", function() {
            salvaModifiche();
        });
        divModificaProfilo.appendChild(buttonSalva);
    }

    async function salvaModifiche()
    {
        let inputFoto = document.querySelector("#inputFoto");
        let inputDescrizione = document.querySelector("#inputDescrizione");

        let url = "ajax/modificaProfilo.php?pathFoto=" + encodeURIComponent(inputFoto.value) + "&descrizione=" + inputDescrizione.value;
        console.log(url);
        let response = await fetch(url);
        let txt = await response.text();
        console.log(txt);
        let data = JSON.parse(txt);
        if(data.status == "OK")
        {
            alert("Modifiche salvate con successo");
            location.reload(); // Ricarica la pagina per vedere le modifiche
        }
        else
        {
            alert("Errore nel salvataggio delle modifiche");
        }
        inputFoto.remove();
        inputDescrizione.remove();
        buttonSalva.remove();
    }

</script>