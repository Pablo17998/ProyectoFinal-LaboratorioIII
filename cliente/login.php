<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mi Cuenta</title>
        <link rel="stylesheet" href="login.css">
    </head>

    <body>
        <div class="login-container">
            <form class="login-form" method="POST">
                <h1>Ingrese su Cuenta</h1>
                <div class="form-group">
                    <label for="username">Usuario:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="clave" required>
                </div>

                <div>
                    <button type="submit" class="btn">Ingresar</button>
                </div> 
            
                <div>
                    <a href="#">Olvidate Tu Contraseña</a>
                </div>
                <div>
                    <a href="registrar.php">No Tienes cuenta?</a>
                </div>

            </form>
        </div>
    </body>
</html>

<?php
    if(isset($_POST["username"])) {
        $d1 = trim($_POST["username"]);
        $d2 = trim($_POST["clave"]);

        $vector = [
            "a"=>$d1,
            "b"=>$d2
        ];
        $vector_json = json_encode($vector);

        $cr = curl_init();
        curl_setopt($cr, CURLOPT_URL, "http://localhost/biblioteca/serverLogin.php");
        curl_setopt($cr, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($cr, CURLOPT_POSTFIELDS, $vector_json);
        curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
        $dtLogin = curl_exec($cr);
        curl_close($cr);

        $dtLgReturn = json_decode($dtLogin);

        if($dtLgReturn->user == $d1 && $dtLgReturn->pw == $d2) {
            header("Location: index.html");
            exit();
        }
        else {
            echo "<script>
                alert('USUARIO Y/O CLAVE INCORRECTA');
            </script>";
        }
    }
?> 