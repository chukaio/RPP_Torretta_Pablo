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
            //
            echo "Probando Postman";
            echo "Testeando";
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
    case '':
        if ($method == 'POST') {
            //
        }
        else if ($method == 'GET'){
            //
        }
    break;
}
