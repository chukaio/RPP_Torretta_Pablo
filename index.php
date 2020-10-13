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
            if (count($datosAuto)==0) {
                echo "No se encuentra ningun vehiculo con dicha patente.";
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
    case '':
        if ($method == 'POST') {
            //
        } else if ($method == 'GET') {
            //
        }
        break;
    case '':
        if ($method == 'POST') {
            //
        } else if ($method == 'GET') {
            //
        }
        break;
    case '':
        if ($method == 'POST') {
            //
        } else if ($method == 'GET') {
            //
        }
        break;
}
