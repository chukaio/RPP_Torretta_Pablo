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
        }
        else if ($method == 'GET'){
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
        }
        else if ($method == 'GET'){
            //
        }
    break;
    case '':
        if ($method == 'POST') {
            //
        }
        else if ($method == 'GET'){
            //
        }
    break;
    case '':
        if ($method == 'POST') {
            //
        }
        else if ($method == 'GET'){
            //
        }
    break;
    case '':
        if ($method == 'POST') {
            //
        }
        else if ($method == 'GET'){
            //
        }
    break;
    case '':
        if ($method == 'POST') {
            //
        }
        else if ($method == 'GET'){
            //
        }
    break;
    case '':
        if ($method == 'POST') {
            //
        }
        else if ($method == 'GET'){
            //
        }
    break;
}
