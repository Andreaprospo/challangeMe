<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <div>
            <label for="">Username o mail</label>    
            <input type="text" name = "identificativo">
        </div>
        <div>
            <label for="">Password</label>
            <input type="password" name = "password">
        </div>
        <button>Login</button>
    </body>
</html>
<script>

    document.addEventListener("DOMContentLoaded", function() {
        document.querySelector("button").addEventListener("click", checkLogin);
    });

    async function checkLogin()
    {
        let url = "ajax/checkLogin.php?identificativo=" + document.getElementsByName("identificativo")[0].value + "&password=" + document.getElementsByName("password")[0].value;
        let response = await fetch(url);
        if(!response.ok)
        {
            throw new Error("Errore HTTP: " + response.status);
        }
        let txt = await response.text();    //NON USARE JSON
        console.log(txt);
        let data = JSON.parse(txt);
        console.log(data);

        if(data["status"] == "ERR")
        {
            alert("Errore: " + data["msg"]);
            return null;
        }
        location.href = "home.php";
    }

</script>
