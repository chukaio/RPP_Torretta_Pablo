<?php
class Archivo
{
    static function saveData(string $path, string $dataAsString) //Appends the string into a file
    {
        $ar = fopen("./" . $path, "a");
        fwrite($ar, $dataAsString . PHP_EOL);
        fclose($ar);
    }

    static function saveObject(string $path, $object) //Appends the object public values separated by a semicolon, with a PHP_EOL at the end
    {
        $ar = fopen("./" . $path, "a");
        $fileContent = "";
        foreach ($object as $key => $value) {
            str_replace("\n", "", $value);
            $fileContent = $fileContent . $value . ";";
        }
        fwrite($ar, substr($fileContent, 0, -1)); //substr function is used to remove the last semicolon
        fclose($ar);
    }

    static function saveAsJson(string $path, $object) //Saves the object as JSON
    {
        $ar = fopen("./" . $path, "a");
        fwrite($ar, json_encode($object) . "\n");
        fclose($ar);
    }

    static function readFileLine($handle) //Reads the next line of the file
    {
        if (feof($handle)) {
            fclose($handle);
            return false;
        } else {
            $string = fgets($handle);
            if ($string != null) {
                return $string;
            } else {
                return null;
            }
        }
    }

    static function readFileLineJson($handle) //Reads the next line of the file
    {
        if (feof($handle)) {
            fclose($handle);
            return false;
        } else {
            $string = fgets($handle);
            if ($string != null) {
                $string = json_decode($string);
                return $string;
            } else {
                return null;
            }
        }
    }

    static function readEntireFile(string $path) //Read the entire file selected in the path
    {
        $fileArray = array();
        $ar = fopen("./" . $path, "r");

        while (!feof($ar)) {
            $string = fgets($ar);
            if ($string != null) {
                array_push($fileArray, $string);
            }
        }
        fclose($ar);
        return $fileArray;
    }

    static function readEntireJson(string $path)
    {
        $fileArray = array();
        $ar = fopen("./" . $path, "r");

        while (!feof($ar)) {
            $string = fgets($ar);
            if ($string != null) {
                $string = json_decode($string);
                array_push($fileArray, $string);
            }
        }
        fclose($ar);
        return $fileArray;
    }

    static function serializeObject(string $path, $object) //Serializes an object into a file
    {
        $ar = fopen("./" . $path, "a");
        fwrite($ar, serialize($object) . PHP_EOL);
        fclose($ar);
    }

    static function deserializeObject(string $path) //Returns an array containing all the objects deserialized
    {
        $objectInfo = array();
        $ar = fopen("./" . $path, "r");

        while (!feof($ar)) {
            $object = unserialize(fgets($ar));
            if ($object != null) {
                array_push($objectInfo, $object);
            }
        }
        fclose($ar);
        return $objectInfo;
    }

    static function locateValInFile($file, $var, $value)
    {
        $fileData = fopen("./archivos/$file", "r");
        $rtn = false;

        while ($json = archivo::readFileLineJson($fileData)) {
            if ($json->$var == $value) {
                $rtn  =  true;
            }
        }

        return $rtn;
    }

    static function setPhotoName($imagen)
    {
        $foto = $imagen;
        $name = explode(".", $foto);
        $extension = pathinfo($foto, PATHINFO_EXTENSION);
        return $name[0] . "_" . date("H-i-s") . "." . $extension;
    }

    static function movePhoto($source, $destination)
    {
        return move_uploaded_file($source, $destination);
    }
}
