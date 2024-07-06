<?php
    ////////////////////////
    // OBTENCION DE DATOS
    $dtLogin = json_decode(file_get_contents("php://input"), true);

    ////////////////////////
    // CONEXION MYSQL
    $db = new mysqli("localhost", "root", "", "biblioteca");

    ////////////////////////
    // CRUD
    function get($db, $dtLogin) {
        $user = trim($dtLogin["a"]);
        $pw = trim($dtLogin["b"]);

        $query = "select usuario, clave from socio where usuario=?";
        $get = $db->prepare($query);

        if($get === false) {
            return "Error al Obtener los datos: ". $db->connect_error;
        }
        else {
            $get->bind_param("s", $user);
            $get->execute();
            $get->bind_result($dt1, $dt2);
            
            if($get->fetch()) {
                $dtUser = $dt1;
                $dtPw = $dt2;

                $vReturn = [
                    "user"=>$dtUser,
                    "pw"=>$dtPw
                ];
            }
            else {
                $vReturn = [
                    "user"=>null,
                    "pw"=>null
                ];
            }
            $get->close();
            
        }
        return json_encode($vReturn);
    }

    ////////////////////////
    // SOLICITUDES HTTP
    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET":
                echo get($db, $dtLogin);
            break;

        default:
                http_response_code(500);
            break;
    }
?>