<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="CSS/styleHome.css">
        <title>Document</title>
    </head>
    <body>
        <div id = "superDiv">
            <div id = "divTop">
                
            </div>
            <div id = "divCorpo">
                <div id="divSfideAccettate"></div>
                <div id="divSfide">
                    <div id = "divBottoniSfide">
                        <button onclick="stampaSfideCompletate()">Sfide accettate</button>
                        <button onclick="stampaNuoveSfide()">Nuove sfide</button>
                    </div>
                    <div id = "sottoDivSfide"></div>
                </div>
                <div id="divSuggeritiSeguiti">
                    <div id = "divBottoni">
                        <button onclick="stampaSeguiti()">Utenti seguiti</button>
                        <button onclick="stampaAllUtenti()">Utenti suggeriti</button>
                    </div>
                    <div id = "divUtenti"></div>
                </div>
            </div>
            <?php
                require_once "footer.php";
            ?>  

        </div>
    </body>
</html>
<script>

    document.addEventListener("DOMContentLoaded", function() {
        stampaSfideAccettate();
        stampaAllUtenti();
    });

    async function stampaAllUtenti()
    {
        let url = "ajax/getAllUtenti.php";
        console.log(url);
        let response = await fetch(url);
        let txt = await response.text();
        console.log(txt);
        let data = JSON.parse(txt);
        let divUtenti = document.querySelector("#divUtenti");
        divUtenti.innerHTML = ""; // Pulisce il contenuto precedente
        if(data.status == "OK")
        {
            for(let i = 0; i < data.data.length; i++)
            {
                let div = document.createElement("div");
                let button = document.createElement("button");
                button.innerHTML = "Segui";
                button.value = data.data[i].username;
                button.addEventListener("click", function(event){
                        segui(event, true);
                });
                div.innerHTML += stampaUtente(data.data[i]);
                div.appendChild(button);
                divUtenti.appendChild(div);
            }   
        }
        else
        {
            let div = document.createElement("div");
            div.innerHTML = "Nessun utente trovato";
            divUtenti.appendChild(div);
        }
    }

    async function stampaSeguiti()
    {
        let url = "ajax/getAllSeguiti.php";
        console.log(url);
        let response = await fetch(url);
        let txt = await response.text();
        console.log(txt);
        let data = JSON.parse(txt);
        let divUtenti = document.querySelector("#divUtenti");
        divUtenti.innerHTML = ""; // Pulisce il contenuto precedente
        if(data.status == "OK")
        {
            for(let i = 0; i < data.data.length; i++)
            {
                let div = document.createElement("div");
                let button = document.createElement("button");
                button.innerHTML = "Smetti di seguire";
                button.value = data.data[i].username;
                button.addEventListener("click", function(event){
                        segui(event, false);
                });
                div.innerHTML += stampaUtente(data.data[i]);
                div.appendChild(button);
                divUtenti.appendChild(div);
            }   
        }
        else
        {
            let div = document.createElement("div");
            div.innerHTML = "Nessun seguito trovato";
            divUtenti.appendChild(div);
        }
    }

    function stampaUtente($data)
    {
        return "<span>" + $data["username"] + "</span>";
    }

    async function segui(event, azione)
    {
        //se true allora segui, altriemnti smetti di seguire
        let url = "ajax/segui.php?username=" + event.target.value + "&azione=" + azione;
        console.log(url);
        let response = await fetch(url);
        let txt = await response.text();
        console.log(txt);
        let data = JSON.parse(txt);
        console.log(txt);
        if(azione)
        {
            stampaAllUtenti(); // Ricarica gli utenti suggeriti
            alert("Segui " + event.target.value);
        }
        else
        {
            stampaSeguiti(); // Ricarica gli utenti seguiti
            alert("Smetti di seguire " + event.target.value);
        }

    }

    function stampaSfida($data)
    {
        let div = document.createElement("div");
        div.innerHTML = $data["descrizione"] + " --- " + $data["dataInizio"] + " --- " + $data["oraInizio"];
        return div;
    }

    async function stampaNuoveSfide()
    {
        let url = "ajax/getAllNuoveSfide.php";
        console.log(url);
        let response = await fetch(url);
        let txt = await response.text();
        console.log(txt);
        let data = JSON.parse(txt);
        let divSfideAccettate = document.querySelector("#sottoDivSfide");
        divSfideAccettate.innerHTML = ""; // Pulisce il contenuto precedente
        if(data.status == "OK")
        {
            for(let i = 0; i < data.data.length; i++)
            {
                let div = document.createElement("div");
                let button = document.createElement("button");
                button.innerHTML = "Accetta sfida";
                button.value = data.data[i].id;
                button.addEventListener("click", function(event){
                        accettaSfida(event);
                });
                div.appendChild(stampaSfida(data.data[i]));
                div.appendChild(button);
                divSfideAccettate.appendChild(div);
            }   
        }
        else
        {
            let div = document.createElement("div");
            div.innerHTML = "Nessuna sfida accettata";
            divSfideAccettate.appendChild(div);
        }
    }

    async function accettaSfida(event)
    {
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

    function stampaSfidaAccettata($data)
    {
        let div = document.createElement("div");
        let buttonFinish = document.createElement("button");
        buttonFinish.innerHTML = "Sfida completata";
        buttonFinish.value = $data["id"];
        buttonFinish.addEventListener("click", function(event){
            completaSfida(event);
        });

        let buttonElimina = document.createElement("button");
        buttonElimina.innerHTML = "Elimina sfida";
        buttonElimina.value = $data["id"];
        buttonElimina.addEventListener("click", function(event){
            eliminaSfida(event);
        });

        div.innerHTML = "<div>" + $data["descrizione"] + " --- " + $data["dataFine"] + " --- " + $data["oraFine"] + "</div>";
        div.appendChild(buttonFinish);
        div.appendChild(buttonElimina);
        return div;
    }

    async function stampaSfideAccettate()
    {
        let url = "ajax/getAllSfideAccettate.php";
        console.log(url);
        let response = await fetch(url);
        let txt = await response.text();
        console.log(txt);
        let data = JSON.parse(txt);
        let divSfideAccettate = document.querySelector("#divSfideAccettate");
        divSfideAccettate.innerHTML = ""; // Pulisce il contenuto precedente
        if(data.status == "OK")
        {
            for(let i = 0; i < data.data.length; i++)
            {
                let div = document.createElement("div");                
                div.appendChild(stampaSfidaAccettata(data.data[i]));
                divSfideAccettate.appendChild(div);
            }   
        }
        else
        {
            let div = document.createElement("div");
            div.innerHTML = "Nessuna sfida accettata";
            divSfideAccettate.appendChild(div);
        }
    }

    async function completaSfida(event)
    {
        let idSfida = event.target.value;
        let url = "ajax/completaSfida.php?idSfida=" + idSfida;
        let response = await fetch(url);
        let txt = await response.text();
        console.log(txt);
        let data = JSON.parse(txt);
        console.log(txt);
        stampaSfideAccettate(); // Ricarica le nuove sfide
    }

    async function eliminaSfida(event)
    {

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

    async function stampaSfideCompletate()
    {
        let url = "ajax/getAllSfideCompletate.php";
        console.log(url);
        let response = await fetch(url);
        let txt = await response.text();
        console.log(txt);
        let data = JSON.parse(txt);
        let sottoDivSfide = document.querySelector("#sottoDivSfide");
        sottoDivSfide.innerHTML = ""; // Pulisce il contenuto precedente
        if(data.status == "OK")
        {
            for(let i = 0; i < data.data.length; i++)
            {
                let div = document.createElement("div");                
                div.appendChild(stampaSfidaCompletata(data.data[i]));
                sottoDivSfide.appendChild(div);
            }   
        }
        else
        {
            let div = document.createElement("div");
            div.innerHTML = "Nessuna sfida completata";
            sottoDivSfide.appendChild(div);
        }
    }

    function stampaSfidaCompletata($data)
    {
        let div = document.createElement("div");
        div.innerHTML = $data["descrizione"] + " --- " + $data["data"] + " --- " + $data["ora"] + " --- " + $data["dataCompletamento"] + " --- " + $data["oraCompletamento"];
        return div;
    }

</script>