<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Formulario de Registro</title>
        <link rel="stylesheet" href="login.css">
    </head>

    <body>
        <div class="login-container">
            <h2>Registro de Usuario</h2>
            <form class="login-form" method="POST">
                <div class="form-group">
                    <label for="first-name">Nombre:</label>
                    <input type="text" id="first-name" name="first-name" required>
                </div>
                <div class="form-group">
                    <label for="last-name">Apellido:</label>
                    <input type="text" id="last-name" name="last-name" required>
                </div>
                <div class="form-group">
                    <label for="username">Usuario:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="gmail">Gmail:</label>
                    <input type="email" id="gmail" name="gmail" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="clave" required>
                </div>
                <button type="submit">Registrarse</button>
            </form>
        </div>
    </body>
</html>

<?php
    if(isset($_POST["first-name"])){
        $d1 = trim($_POST["first-name"]);
        $d2 = trim($_POST["last-name"]);
        $d3 = trim($_POST["username"]);
        $d4 = trim($_POST["gmail"]);
        $d5 = trim($_POST["clave"]);

        $vData = [
            "a"=>$d1,
            "b"=>$d2,
            "c"=>$d3,
            "d"=>$d4,
            "e"=>$d5
        ];
        $vData_json = json_encode($vData);
    
        $cr = curl_init();
        curl_setopt($cr, CURLOPT_URL, "http://localhost/biblioteca/serverRegistrarse.php");
        curl_setopt($cr, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($cr, CURLOPT_POSTFIELDS, $vData_json);
        curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
        $dtCliente = curl_exec($cr);
        curl_close($cr);

        echo "<script>
            alert('USUARIO REGISTRADO CORRECTAMENTE')
        </script>";

        /*print_r($dtCliente->ok);
        die();*/

        //$dtReturn = json_decode($dtCliente);

        /*print_r($dtReturn->ok);
        die();*/
    }
?>
