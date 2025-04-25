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
                <div id="divNuoveSfide"></div>
                <div id="divSuggeritiSeguiti"></div>
            </div>
            <?php
                require_once "footer.php";
            ?>  

        </div>
    </body>
</html>
<script>

    document.addEventListener("DOMContentLoaded", function() {
        stampaNuoveSfide();
        stampaSfideAccettate();
    });

    async function stampaNuoveSfide()
    {
        let url = "ajax/getAllNuoveSfide.php";
        console.log(url);
        let response = await fetch(url);
        let txt = await response.text();
        console.log(txt);
        let data = JSON.parse(txt);
        let divSfideAccettate = document.querySelector("#divNuoveSfide");
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
                div.innerHTML += stampaSfida(data.data[i]);
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
        let divSfideAccettate = document.querySelector("#divSfideAccettate");
        divSfideAccettate.innerHTML += stampaSfida(data.data); // Pulisce il contenuto precedente
        stampaNuoveSfide(); // Ricarica le nuove sfide
    }

    function stampaSfida($data)
    {
        return "<span>" + $data["descrizione"] + " --- " + $data["dataFine"] + " --- " + $data["oraFine"] + "</span>";
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

                let buttonCompleta = document.createElement("button");
                buttonCompleta.innerHTML = "Sfida completata";
                buttonCompleta.value = data.data[i].id;
                buttonCompleta.addEventListener("click", function(event){
                    completaSfida(event);
                });
                let buttonElimina = document.createElement("button"); 
                buttonElimina.innerHTML = "Elimina sfida";
                buttonElimina.value = data.data[i].id;
                buttonElimina.addEventListener("click", function(event){
                    eliminaSfida(event);
                });
                
                div.innerHTML += stampaSfida(data.data[i]);
                div.appendChild(buttonCompleta);
                div.appendChild(buttonElimina);
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
        let url = "ajax/completaSfida.php?idSfida=" + idSfida;
        console.log(url);
        return true;
        let idSfida = event.target.value;
        let response = await fetch(url);
        let txt = await response.text();
        console.log(txt);
        let data = JSON.parse(txt);
        console.log(txt);
        let divSfideAccettate = document.querySelector("#divSfideAccettate");
        divSfideAccettate.innerHTML += stampaSfida(data.data); // Pulisce il contenuto precedente
        stampaSfideAccettate(); // Ricarica le nuove sfide
    }

    async function completaSfida(event)
    {
        let url = "ajax/eliminaSfida.php?idSfida=" + idSfida;
        console.log(url);
        return true;
        let idSfida = event.target.value;
        let response = await fetch(url);
        let txt = await response.text();
        console.log(txt);
        let data = JSON.parse(txt);
        console.log(txt);
        let divSfideAccettate = document.querySelector("#divSfideAccettate");
        divSfideAccettate.innerHTML += stampaSfida(data.data); // Pulisce il contenuto precedente
        stampaSfideAccettate(); // Ricarica le nuove sfide
    }
</script>