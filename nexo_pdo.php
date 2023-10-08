<?php

require_once "./alumno.php";

use Medina\Alumno;

if (!is_dir("./archivos/")) {
    mkdir("./archivos/", 0777, true);
}
session_start();


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
                    $a->set_foto();
                    if ($a->agregar_bd()) {
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
                    $modif->set_foto();

                    if ($modif->modificar_bd()) {
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




                    if (Alumno::borrar_bd($legajo)) {
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
    if (isset($accion)) {
        switch (strtolower($accion)) {
            case 'listar':
                $array_alumnos = Alumno::listar_bd();
                if (isset($array_alumnos)) {
                    var_dump($array_alumnos);
                }

                break;
            case 'obtener':
                $legajo = isset($_GET["legajo"]) ? trim($_GET["legajo"]) : null;

                $alum = Alumno::obtener_bd($legajo);
                if (isset($alum)) {
                    var_dump($alum);
                }
                break;
            case 'redirigir':
                /**Se invoca al método que verifica la existencia de un alumno por su legajo.
Si se encuentra:
redirigir hacia la página 'principal.php' (crearla en el raíz).
Si no se encuentra, mostrar el siguiente mensaje:
'El alumno con legajo 'xxx' no se encuentra en el listado'
Siendo 'xxx' el valor del legajo enviado por POST. */
                $legajo = isset($_GET["legajo"]) ? trim($_GET["legajo"]) : null;
      
                if (isset($legajo)) {
                    if(Alumno::obtener_bd($legajo))
                    {
                        Alumno::redirigir_db($legajo);
             
                     
                }else
                {
                    echo 'El alumno con legajo ' . $legajo . ' no se encuentra en el listado';
                }
            }
                break;
            case 'listar_pdf':
                /**Enviar (por GET) a la página ./nexo_poo_foto.php:
                 *-accion => 'listar_pdf'
Recuperar el valor enviado y mostrar el contenido completo del archivo
./archivos/alumnos_foto.txt.
Cada registro se mostrará, en una tabla, con el siguiente formato (un
registro por fila):
legajo - apellido - nombre - foto (imagen)
El documento PDF deberá tener:
                 *-Encabezado (apellido y nombre del alumno a la izquierda y número de
página a la derecha)
                 *-Cuerpo (Título del listado, listado completo de los alumnos con su
respectiva foto)
                 *-Pie de página (fecha actual, centrada). */

                require_once "./listar_pdf.php";


                break;
            default:

                break;
        }
    }
}
