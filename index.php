<?php

//require __DIR__ . "/vendor/autoload.php";
//require_once "";

$path = $_SERVER['PATH_INFO'] ?? "";
$method = $_SERVER['REQUEST_METHOD'];

echo $path . " y " . $method . "<br>";

switch ($path) {
    case '/':
        if ($method == 'POST') {
            //
        }
        else if ($method == 'GET'){
            //
        }
    break;
}
