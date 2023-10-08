<?php

if (!is_dir("./archivos/")) {
    mkdir("./archivos/", 0777, true);
}
$path_alumnos = "./archivos/alumnos.txt";

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $accion = isset($_POST["accion"]) ? trim($_POST["accion"]) : null;
    if (isset($accion)) {
        switch (strtolower($accion)) {
            case 'agregar':


                $nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]) : null;

                $apellido = isset($_POST["apellido"]) ? trim($_POST["apellido"]) : null;


                $legajo = isset($_POST["legajo"]) ? trim($_POST["legajo"]) : null;
                if (isset($accion, $apellido, $nombre, $legajo) && "agregar" == strtolower($accion)) {
                    $contenido = "$legajo-$apellido-$nombre";
                    $pFile = fopen($path_alumnos, "a");
                    if ($pFile !== false) {

                        if (fwrite($pFile, $contenido . "\r\n") !== false) {
                            echo "alumno guardado";
                        }

                        fclose($pFile);
                    }
                } else {
                    echo "no se pudo guardar alumno Error en parametros";
                }

                break;
            case 'verificar':

                $encontrado = false;
                $legajo = isset($_POST["legajo"]) ? trim($_POST["legajo"]) : null;
                if (isset($legajo)) {
                    $pFile = fopen($path_alumnos, "r");
                    if ($pFile !== false) {

                        while (!feof($pFile)) {
                            $linea = fgets($pFile);
                            if (($linea !== false) && !empty($linea)) {
                                $array = explode("-", $linea);
                                if ($array[0] == $legajo) {
                                    $encontrado = true;
                                    break;
                                }
                            }
                        }
                        fclose($pFile);
                        if ($encontrado) {
                            echo "El alumno con legajo $legajo se encuetra en el listado";
                        } else {
                            echo "El alumno con legajo $legajo NO se encuetra en el listado";
                        }
                    }
                }

                break;

            case 'modificar':
                $modificado = false;
                $nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]) : null;

                $apellido = isset($_POST["apellido"]) ? trim($_POST["apellido"]) : null;

                $legajo = isset($_POST["legajo"]) ? trim($_POST["legajo"]) : null;
                if (isset($nombre, $apellido, $legajo)) {
                    $contenido = "";
                    $pFile = fopen($path_alumnos, "r");
                    while (!feof($pFile)) {
                        $linea = fgets($pFile);
                        if (($linea !== false) && !empty($linea)) {
                            $array = explode("-", $linea);
                            if ($array[0] == $legajo) {
                                $modificado = true;
                                $contenido .= "$legajo-$nombre-$apellido" . "\r\n";
                            } else {
                                $contenido .= $linea;
                            }
                        }
                    }
                    var_dump($contenido);
                    fclose($pFile);
                    if ($modificado) {
                        file_put_contents($path_alumnos, $contenido);

                        echo "El alumno con legajo $legajo  se ha modificado";
                    } else {
                        echo "El alumno con legajo $legajo NO se encontro";
                    }
                }
                break;

            case 'borrar':

                $eliminado = false;
                $legajo = isset($_POST["legajo"]) ? trim($_POST["legajo"]) : null;
                if (isset($legajo)) {
                    $contenido = "";
                    $pFile = fopen($path_alumnos, "r");
                    if ($pFile !== false) {


                        while (!feof($pFile)) {

                            $linea = fgets($pFile);
                            if (($linea !== false) && !empty($linea)) {
                                $array = explode("-", $linea);
                                if ($array[0] == $legajo) {
                                    $eliminado = true;
                                    continue;
                                }
                                $contenido .= $linea;
                            }
                        }
                        fclose($pFile);
                        var_dump($contenido);
                    }
                    if ($eliminado) {
                        file_put_contents($path_alumnos, $contenido);
                        echo "El alumno con legajo $legajo se ha borrado con exito";
                    } else {

                        echo "El alumno con legajo $legajo NO se encuentra en el listado";
                    }
                }
                break;
            default:

                # code...
                break;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $accion = isset($_GET["accion"]) ? trim($_GET["accion"]) : null;



    if (isset($accion) && "listar" == strtolower($accion)) {
        $pFile = fopen($path_alumnos, "r");
        if ($pFile !== false) {

            $contenido = fread($pFile, filesize($path_alumnos));
            if ($contenido !== false) {


                $contenido = str_replace("\r\n", "</br>", $contenido);
                echo $contenido;
            } else {
                echo "ERROR EN LISTADO";
            }

            fclose($pFile);
        }
    }
}
