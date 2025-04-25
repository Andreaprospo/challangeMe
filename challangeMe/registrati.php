<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <div>
            <div>
                <label for="">Username</label>
                <input type="text" name="username">
            </div>
            <div>
                <label for="">Mail</label>
                <input type="mail" name="mail">
            </div>
            <div>
                <label for="">Password</label>
                <input type="password" name="password">
            </div>
            <button>Registrati</button>
        </div>
    </body>
</html>
<script>

    document.addEventListener("DOMContentLoaded", function() {
        document.querySelector("button").addEventListener("click", checkRegistrati);
    });

    async function checkRegistrati()
    {
        let url = "ajax/registrati.php?username=" + document.getElementsByName("username")[0].value + "&mail=" + document.getElementsByName("mail")[0].value + "&password=" + document.getElementsByName("password")[0].value;
        console.log(url);
        let response = await fetch(url);
        let txt = await response.text();    //NON USARE JSON
        console.log(txt);
        let json = JSON.parse(txt);
        console.log(json);
        if(json["status"] == "ERR")
        {
            alert("Errore: " + json["msg"]);
            return null;
        }
        alert("Registrazione avvenuta con successo");
        location.href = "login.php";
    }

</script>