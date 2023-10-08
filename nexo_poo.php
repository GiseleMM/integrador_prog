<?php

require_once "./alumno.php";

use Medina\Alumno;

if (!is_dir("./archivos/")) {
    mkdir("./archivos/", 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $accion = isset($_POST["accion"]) ? trim($_POST["accion"]) : null;
    if (isset($accion)) {
        switch (strtolower($accion)) {
            case 'agregar':


                $nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]) : null;
                $apellido = isset($_POST["apellido"]) ? trim($_POST["apellido"]) : null;
                $legajo = isset($_POST["legajo"]) ? trim($_POST["legajo"]) : null;
                if (isset($accion, $apellido, $nombre, $legajo)) {

                    $a = new Alumno($legajo, $nombre, $apellido);
                    if ($a->agregar()) {
                        echo "Alumno guardado";
                    } else {
                        echo "No se pudo guardar alumno";
                    }
                }

                break;
            case 'verificar':

                $encontrado = false;
                $legajo = isset($_POST["legajo"]) ? trim($_POST["legajo"]) : null;
                if (isset($legajo)) {

                    if (Alumno::verificar($legajo)) {
                        echo "El alumno con legajo $legajo se encuetra en el listado";
                    } else {
                        echo "El alumno con legajo $legajo NO se encuetra en el listado";
                    }
                }

                break;

            case 'modificar':

                $modificado = false;
                $nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]) : null;
                $apellido = isset($_POST["apellido"]) ? trim($_POST["apellido"]) : null;
                $legajo = isset($_POST["legajo"]) ? trim($_POST["legajo"]) : null;
                if (isset($nombre, $apellido, $legajo)) {
                  
                    $modif = new Alumno($legajo, $nombre, $apellido);

                    if ($modif->modificar()) {
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
                    
              
                    
                    if (Alumno::eliminar($legajo)) {
                        echo "El alumno con legajo $legajo se ha borrado con exito";
                    } else {

                        echo "El alumno con legajo $legajo NO se encuentra en el listado";
                    }
                }
                break;
            default:

                break;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $accion = isset($_GET["accion"]) ? trim($_GET["accion"]) : null;

    if (isset($accion) && "listar" == strtolower($accion)) {
        Alumno::listar();
    }
}
