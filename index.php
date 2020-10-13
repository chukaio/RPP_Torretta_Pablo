<?php

require_once __DIR__ . "/include/archivo.php";
require_once __DIR__ . "/include/token.php";

$pathInfo = explode("/", $_SERVER['PATH_INFO']);
$path = $pathInfo[1];
$method = $_SERVER['REQUEST_METHOD'] ?? "";

//$patente = $pathInfo[2];

echo $path . " y " . $method . "<br>";

switch ($path) {
    case 'registro':
        if ($method == 'POST') {
            //01 --> Registrar un usuario con los siguientes datos: email, tipo de usuario, password y foto. El tipo de usuario puede ser admin o user. Validar que el mail no esté registrado previamente.
            $email = $_POST['email'] ?? "";
            $tipo = $_POST['tipo'] ?? "";
            $password = $_POST['password'] ?? "";
            $imagen = $_FILES['imagen']['name'] ?? "";

            if (archivo::locateValInFile("user.json", "email", $email) == true) {
                echo "Email ya registrado";
                die();
            } else {
                $imagen = archivo::setPhotoName($imagen);
                $source = $_FILES["imagen"]["tmp_name"] ?? "";
                $destination = "./archivos/" . $imagen;
                $uploaded = move_uploaded_file($source, $destination);
                if ($uploaded == 1) {
                    $datos = array(
                        "email" => $email,
                        "tipo" => $tipo,
                        "password" => $password,
                        "imagen" => $imagen
                    );
                    archivo::saveAsJson("archivos/user.json", $datos); //Guarda los datos en un archivo
                    echo "Usuario creado satisfactoriamente!.";
                } else {
                    echo "Error al cargar la imagen.";
                }
            }
        } else if ($method == 'GET') {
            //
        }
        break;
    case 'login':
        if ($method == 'POST') {
            //02 --> Los usuarios deberán loguearse y se les devolverá un token con email y tipo en caso de estar registrados, caso contrario se informará el error.
            $email = $_POST['email'] ?? "";
            $password = $_POST['password'] ?? "";

            $fileData = fopen("./archivos/user.json", "r");
            while ($json = Archivo::readFileLineJson($fileData)) {
                if (archivo::locateValInFile("user.json", "email", $email) && archivo::locateValInFile("user.json", "password", $password)) {
                    $payload = array(
                        "email" => $email,
                        "tipo" => $json->tipo
                    );

                    echo Token::encode($payload);
                    fclose($fileData);
                    die();
                }
            }
            echo "Login inválido";
            fclose($fileData);
        } else if ($method == 'GET') {
            //
        }
        break;
    case 'vehiculo':
        if ($method == 'POST') {
            //03 --> Se deben guardar los siguientes datos: marca, modelo, patente y precio. Los datos se guardan en el archivo de texto vehiculos.xxx, tomando la patente como identificador(la patente no puede estar repetida).
            $userData = Token::decode($_SERVER['HTTP_TOKEN']);
            $marca = $_POST['marca'] ?? "";
            $modelo = $_POST['modelo'] ?? "";
            $patente = $_POST['patente'] ?? "";
            $precio = $_POST['precio'] ?? "";
            if ($userData == null) {
                echo "Token inválido.";
                die();
            } else {
                if (archivo::locateValInFile("vehiculos.json", "patente", $patente)) {
                    echo "El auto que desea ingresar ya se encuentra cargado.";
                } else {
                    $datos = array(
                        "marca" => $marca,
                        "modelo" => $modelo,
                        "patente" => $patente,
                        "precio" => $precio
                    );
                    archivo::saveAsJson("archivos/vehiculos.json", $datos);
                    echo "Auto ingresado correctamente.";
                }
            }
        } else if ($method == 'GET') {
            //
        }
        break;
    case 'patente':
        if ($method == 'POST') {
            //
        } else if ($method == 'GET') {
            //04 --> Se ingresa marca, modelo o patente, si coincide con algún registro del archivo se retorna las ocurrencias, si no coincide se debe retornar “No existe xxx” (xxx es lo que se buscó) La búsqueda tiene que ser case insensitive.
            $patente = $pathInfo[2];
            echo $patente . "<br>";

            $datosAuto = array();
            $userData = Token::decode($_SERVER['HTTP_TOKEN']);
            if ($userData == null) {
                echo "Token inválido.";
                die();
            }
            $fileData = fopen("./archivos/vehiculos.json", "r");
            while ($json = archivo::readFileLineJson($fileData)) {
                if (archivo::locateValInFile("vehiculos.json", "patente", $patente)) {
                    array_push($datosAuto, $json);
                    break;
                }
            }
            fclose($fileData);
            if (count($datosAuto) == 0) {
                echo "No existe ningun vehiculo con la patente \"" . $patente . "\"";
            } else {
                foreach ($datosAuto as $index => $auto) {
                    echo "---------------------------<br>";
                    echo ("Marca: $auto->marca \n") . "<br>";
                    echo ("Modelo: $auto->modelo \n") . "<br>";
                    echo ("Patente: $auto->patente \n") . "<br>";
                    echo ("Precio: $auto->precio \n") . "<br>";
                    echo "---------------------------<br>";
                }
            }
        }
        break;
    case 'servicio':
        if ($method == 'POST') {
            //05 --> Se recibe el nombre del servicio a realizar: id, tipo(de los 10.000km, 20.000km, 50.000km), precio y demora, y se guardará en el archivo tiposServicio.xxx.
            $userData = Token::decode($_SERVER['HTTP_TOKEN']);

            $id = $_POST['id'] ?? "";
            $tipo = $_POST['tipo'] ?? "";
            $precio = $_POST['precio'] ?? "";
            $demora = $_POST['demora'] ?? "";
            if ($userData == null) {
                echo "Token inválido.";
                die();
            } else {
                if (archivo::locateValInFile("tiposServicio.json", "id", $id)) {
                    echo "El servicio que desea ingresar ya se encuentra cargado.";
                } else {
                    if ($tipo != "10000" && $tipo != "20000" && $tipo != "50000") {
                        $tipo = "";
                    }
                    $datos = array(
                        "id" => $id,
                        "tipo" => $tipo,
                        "precio" => $precio,
                        "demora" => $demora
                    );
                    archivo::saveAsJson("archivos/tiposServicio.json", $datos);
                    echo "Servicio ingresado correctamente.";
                }
            }
        } else if ($method == 'GET') {
            //
        }
        break;
    case 'turno':
        if ($method == 'POST') {
            //06 --> Se recibe patente y fecha (día) y se debe guardar en el archivo turnos.xxx, fecha, patente, marca, modelo, precio y tipo de servicio. Si no hay cupo o la patente no existe informar cada caso particular.
            $userData = Token::decode($_SERVER['HTTP_TOKEN']);
            $id = $_POST['id'] ?? "";
            $patente = $_POST['patente'] ?? "";
            $fecha = $_POST['fecha'] ?? "";
            if ($userData == null) {
                echo "Token inválido.";
                die();
            } else {
                $datosAuto = null; //array();
                $datosTurno = null; //array();

                $fileData = fopen("./archivos/vehiculos.json", "r");
                while ($json = archivo::readFileLineJson($fileData)) {
                    if (archivo::locateValInFile("vehiculos.json", "patente", $patente)) {
                        //array_push($datosAuto, $json);
                        $datosAuto = $json;
                        break;
                    }
                }
                fclose($fileData);
                $fileData2 = fopen("./archivos/tiposServicio.json", "r");
                while ($json = archivo::readFileLineJson($fileData2)) {
                    if (archivo::locateValInFile("tiposServicio.json", "id", $id)) {
                        //array_push($datosTurno, $json);
                        $datosTurno = $json;
                        break;
                    }
                }
                fclose($fileData2);
                if (is_null($datosAuto) || is_null($datosTurno)) {
                    if (is_null($datosAuto)) {
                        echo "La patente \"" . $patente . "\" no existe";
                    } else if (is_null($datosTurno)) {
                        echo "No hay cupo disponible";
                    }
                } else {
                    $datos = array(
                        "fecha" => $fecha,
                        "patente" => $patente,
                        "marca" => $datosAuto->marca,
                        "modelo" => $datosAuto->modelo,
                        "precio" => $datosAuto->precio,
                        "tipo" => $datosTurno->tipo
                    );
                    archivo::saveAsJson("archivos/turnos.json", $datos);
                    echo "Turno reservado correctamente.";
                }
            }
        } else if ($method == 'GET') {
            //

        }
        break;
    case 'stats':
        if ($method == 'POST') {
            //
        } else if ($method == 'GET') {
            //07 --> Puede recibir el tipo de servicio, si lo incluye, muestra un listado con los servicios de ese tipo realizados, si no muestra todos los servicios.
            $userData = Token::decode($_SERVER['HTTP_TOKEN']);
            $tipo = $_GET['tipo'] ?? "";
            if ($userData == null) {
                echo "Token inválido.";
                die();
            } else {
                if ($userData->tipo != "admin") {
                    echo "Se requiere permisos de tipo admin";
                } else {
                    if (empty($_GET['tipo'])) {
                        //getall
                    } else {
                        if ($tipo != "10000" && $tipo != "20000" && $tipo != "50000") {
                            $tipo = "";
                        }
                        if ($tipo == "") {
                            //getall
                        } else {
                            //get specific
                        }
                    }
                }
            }
        }
        break;
}
