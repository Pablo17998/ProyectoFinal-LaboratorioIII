<?php
    require "vendor/autoload.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    $mail = new PHPMailer(true);
    
    /////////////////////////////
    // OBTENCION DE DATOS
    header('Content-Type: application/json');
    $dtTienda = json_decode(file_get_contents("php://input"), true);

    ////////////////////////////
    // MYSQL CONEXION
    $db = new mysqli("localhost", "root", "", "biblioteca");

    ////////////////////////////
    // CRUD 
    function correo($db, $dtTienda) {
        $user = trim($dtTienda["vU"]);

        $query = "select email from socio where usuario=?";
        $get = $db->prepare($query);

        if($get === false) {
            return "Error al Obtener los datos: ". $db->connect_error;
        }
        else {
            $get->bind_param("s", $user);
            $get->execute();
            $get->bind_result($c1);
            
            if($get->fetch()) {
                $correo = $c1;

                $mail = [
                    "correo"=>$correo
                ];
            }
            else {
                $mail = [
                    "correo"=>null
                ];
            }
            $get->close();
            
        }
        return $mail;
    }
    $correo = correo($db, $dtTienda);

    function reserva($db, $dtTienda) {
        $precio = trim($dtTienda["vP"]);
        $titulo = trim($dtTienda["vT"]);
        $user = trim($dtTienda["vU"]);

        $query = "select id, email from socio where usuario= ?";
        $get = $db->prepare($query);

        if($get === false) {
            return "Error al Obtener los datos: ". $get->connect_error;
        }
        else {
            $get->bind_param("s", $user);
            $get->execute();
            $get->bind_result($c0, $c1);
            
            if($get->fetch()) {
                $id = $c0;
                $correo = $c1;

                $vCorreo = [
                    "id"=>$id,
                    "correo"=>$correo
                ];
                
                $get->close();

                if(isset($id)) {
                    $query = "insert into reserva(titulo, precio, socio_id) values(?, ?, ?)";
                    $send = $db->prepare($query);
        
                    if($send === false) {
                        return "Error al Guardar los datos: ". $send->connect_error;
                    }
                    else {
                        $send->bind_param("sss", $titulo, $precio, $id);
                        $send->execute();
                        $send->close();
        
                        $vCorreo = [
                            "titulo"=>$titulo,
                            "precio"=>$precio
                            
                        ];
                        return $vCorreo;
                    }
                }
            }
            else {
                $vCorreo = [
                    "id"=>null,
                    "correo"=>null
                ];
                return $vCorreo;
            }
        }
        
    }
    $vUser = reserva($db, $dtTienda);
    
    /*print_r($dtUser);
    die();*/

    switch($_SERVER["REQUEST_METHOD"]) {
        case "POST":
                $retorno = json_encode(reserva($db, $dtTienda));
                echo $retorno;
                //send($db, $dtTienda, $vCorreo);

                correo($db, $dtTienda);
            break;
        
        default:
                http_response_code(500);
            break;
    }

    ////////////////////////////
    // PHPMailer
    try {
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "agaewq98@gmail.com";
        $mail->Password = "kieyvjmdufvjsqsa";
        $mail->SMTPSecure = "ssl";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  
        $mail->Port = 465;

        
        if(isset($correo)) {
            $vMail = trim($correo["correo"]);

            $mail->setFrom("agaewq98@gmail.com", "<No Responder>"); 
            $mail->addAddress($vMail, ""); 
        }
        else {
            echo "Error al obtener el correo";
        } 
        
        if(isset($vUser)) {
            $precio = trim($vUser["precio"]);
            $titulo = trim($vUser["titulo"]);

            $contenido = "<p>!GRACIAS POR SU RESERVA!</p><br>

            
                <strong>Libro Reservado</strong>: ".$titulo."<br>
                <strong>Precio de la Reserva</strong>: $".$precio."<br><br>

                <p>-Recuerde que la fecha limite del retiro es hasta dos dias despues de la reserva-</p>
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

        if(isset($correo)) {
            $vMail = trim($correo["correo"]);

            echo "Mensaje Enviado a ". $vMail;
        }
    } catch(Exception $e) {    
        echo "No pudo enviarse el mensaje: {$mail->ErrorInfo}";
    }
?>