<?php
    require "vendor/autoload.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    $mail = new PHPMailer(true);

    ///////////////////////////////////
    // OBTENCION DE DATOS DEL CLIENTES
    $dtCliente = json_decode(file_get_contents("php://input"), true);

    ///////////////////////////////////
    // MYSQL CONEXION
    $db = new mysqli("localhost", "root", "", "biblioteca");

    ///////////////////////////////////
    // CRUD
    function save($db, $dtCliente) {
        if(isset($dtCliente)) {
            $user = trim($dtCliente["c"]);
            $correo = trim($dtCliente["d"]);
            $pw = trim($dtCliente["e"]);

            $query = "insert into socio(usuario, clave, email) values(?, ?, ?)";
            $send = $db->prepare($query);

            if($send === false) {
                return "Error al GUARDAR los datos: ". $send->connect_error;
            }
            else {
                $send->bind_param("sss", $user, $pw, $correo);
                $send->execute();
                $send->close();
            }
        }
    }

    ///////////////////////////////////
    // Return DATA
    /*function rData ($dtCliente) {
        $user = trim($dtCliente["c"]);
        $correo = trim($dtCliente["d"]);
        $pw = trim($dtCliente["e"]);

        $vFinal = [
            "ok"=>true,
            "u"=>$user,
            "c"=>$correo,
            "pw"=>$pw
        ];
        return json_encode($vFinal);
    }*/

    ///////////////////////////////////
    // SOLICITUDES HTTP
    switch($_SERVER["REQUEST_METHOD"]) {
        case "POST": 
                save($db, $dtCliente);
            break;

        default:    
                http_response_code(500);
            break;
    }

    ///////////////////////////////////
    // PHPMailer
    try {
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "<PONE_TU_CORREO_AQUI>";
        $mail->Password = "<CLAVE_DE_TU_CORREO>";
        $mail->SMTPSecure = "ssl";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  
        $mail->Port = 465;

        if(isset($dtCliente)) {
            $correo = trim($dtCliente["d"]);

            $mail->setFrom("<PONE_TU_CORREO_AQUI>", "<No Responder>"); 
            $mail->addAddress($correo, ""); 
        }
        else {
            echo "Error al obtener el correo";
        } 
        
        if(isset($dtCliente)) {
            $name = trim($dtCliente["a"]);
            $lastname = trim($dtCliente["b"]);
            $user = trim($dtCliente["c"]);
            $pw = trim($dtCliente["e"]);

            $contenido = "<p>Bienvenido ".$name." ".$lastname.".</p><br>

            
                <strong>Usuario</strong>: ".$user."<br>
                <strong>Clave</strong>: ".$pw."<br><br>

                <p>-Por favor, no comparta estos datos con nadie-</p>
            ";
        } 
        else {
            echo "Error al obtener los datos del cliente";
        }

        $mail->isHTML(true); 
        $mail->Subject = "LIBRERIA SAVIGOD"; 
        $mail->Body = $contenido; 
        //$mail->AltBody = ""; // Body alternativo para clientes que no soportan HTML (opcional utilizarlo)
        $mail->send();
        echo "Mensaje Enviado a ". $correo;
    } catch(Exception $e) {    
        echo "No pudo enviarse el mensaje: {$mail->ErrorInfo}";
    }
?>