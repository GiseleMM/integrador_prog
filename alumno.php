<?php

namespace Medina;

use PDO;
use PDOException;

class Alumno
{
    public int $legajo;
    public string $nombre;
    public string $apellido;
    public string $foto;
    public int $id;
    private $path = "./archivos/alumnos_foto.txt";
    function __construct($legajo = -1, $nombre = "", $apellido = "", $foto = "",$id=-1)
    {
        $this->legajo = $legajo;
        $this->apellido = $apellido;
        $this->nombre = $nombre;
        $this->foto = $foto;
        $this->id=$id;
    }

    /**NOTA: agregar un método en alumno que reciba como parámetro un legajo y retorne
un objeto de tipo Alumno. */
public static function obtener($legajo):Alumno|null
{
    $alumno=null;
    echo "entre a obtner";
    if(isset($legajo))
    {
        $pFile = fopen("./archivos/alumnos_foto.txt", "r");
        if ($pFile !== false) {


            while (!feof($pFile)) {
                $linea = fgets($pFile);
                if (($linea !== false) && !empty($linea)) {
                    $array = explode("-", $linea);
                    if ($array[0] == $legajo) {
                   $alumno=new Alumno($legajo,$array[1],$array[2],$array[3]);     
                        break;
                    }
                }
            }
            fclose($pFile);
        }
    }
    return $alumno;
}
    public function set_foto()
    {
        if (!is_dir("./fotos/")) {
            $foto = __DIR__ . "/fotos/";
            mkdir($foto, 0777, true);
        }
        $foto = $_FILES["foto"];
        $destino = "./fotos/";
        if (isset($foto) && $foto["error"] == 0) {
            $errores = [];
            $tam_max = 1000000;

            $array = getimagesize($foto["tmp_name"]);
            if ($array == false) {
                array_push($errores, "No es una imagen");
            }
            if ($foto["size"] > $tam_max) {
                array_push($errores, "Tamaño superior a los $tam_max");
            }
            if (count($errores) > 0) {
                var_dump($errores);
                $this->foto = "";
            } else {

                $extension = pathinfo($foto["name"], PATHINFO_EXTENSION);
                $destino .= "$this->legajo.$extension";
                var_dump($destino);
                move_uploaded_file($foto["tmp_name"], $destino);
                $this->foto = "$this->legajo.$extension";
            }
        }
    }
    public function agregar(): bool
    {
        $agregado = false;
        $contenido = "$this->legajo-$this->nombre-$this->apellido-$this->foto" . "\r\n";
        $pFile = fopen($this->path, "a");
        if ($pFile !== false) {

            if (fwrite($pFile, $contenido) !== false) {
                $agregado = true;
            }
            fclose($pFile);
        }
        return $agregado;
    }

    public function modificar(): bool
    {
        $modificado = false;
        $contenido = "";
        $pFile = fopen($this->path, "r");
        if ($pFile !== false) {


            while (!feof($pFile)) {
                $linea = fgets($pFile);
                if (($linea !== false) && !empty($linea)) {
                    $array = explode("-", $linea);
                    if ($array[0] == $this->legajo) {
                        $modificado = true;
                        $contenido .= "$this->legajo-$this->nombre-$this->apellido-$this->foto" . "\r\n";
                    } else {
                        $contenido .= $linea;
                    }
                }
            }
            var_dump($contenido);
            fclose($pFile);
        }
        if ($modificado) {
            file_put_contents($this->path, $contenido);
        }

        return $modificado;
    }




    public  static function eliminar($legajo): bool
    {
        $eliminado = false;
        $contenido = "";
        $foto = "";
        $pFile = fopen("./archivos/alumnos_foto.txt", "r");
        if ($pFile !== false) {


            while (!feof($pFile)) {
                $linea = fgets($pFile);
                if (($linea !== false) && !empty($linea)) {
                    $array = explode("-", $linea);
                    if ($array[0] == $legajo) {
                        $eliminado = true;
                        $foto = end($array);
                        continue;
                    }

                    $contenido .= $linea;
                }
            }
            var_dump($contenido);
            fclose($pFile);
        }
        if ($eliminado) {
            //fotos
            file_put_contents("./archivos/alumnos_foto.txt", $contenido);
            var_dump($foto);


            unlink("./fotos/" . trim($foto)); ///ELIMNO FOTO
            //copy("./fotos/".$foto,"destino")


        }

        return $eliminado;
    }


    public static function verificar($legajo): bool
    {
        $encontrado = false;

        $pFile = fopen("./archivos/alumnos_foto.txt", "r");
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
        }

        return $encontrado;
    }
    public static function listar()
    {

        $contenido = file_get_contents("./archivos/alumnos_foto.txt");
        if ($contenido !== false) {

            $contenido = str_replace("\r\n", "</br>", $contenido);
            return $contenido;
        } else {
            echo "ERROR EN LISTADO DE ALUMNOS";
        }
    }
    /**BASE DE DATOS--------------------------------------------------------------------------
     * Tomando como punto de partida los ejercicios anteriores (Ejercicio_clase04),
se pide:
Agregar la base alumno_pdo (ver alumno_pdo.sql)
Tabla: alumnos
id(pk) - legajo - apellido - nombre - foto (el path)

La foto se seguirá guardando en ./fotos y su nombre será:
● ./legajo.extension
Agregar los métodos:
● agregar_bd
● listar_bd --> retorna array de Alumno
● obtener_bd --> retorna Alumno
● modificar_bd
● borrar_bd
● redirigir_bd

Probar que el CRUD funcione correctamente en nexo_pdo.php
     */
    public function agregar_bd():bool
    {
        $agregado=false;
        try {
            $pdo=new PDO("mysql:host=localhost;dbname=alumno_pdo","root","");
           $sql=$pdo->prepare("INSERT INTO alumnos (legajo,apellido,nombre,foto)VALUES(:legajo,:apellido,:nombre,:foto);");
           $sql->bindParam(":legajo",$this->legajo,PDO::PARAM_INT);
           $sql->bindParam(":apellido",$this->apellido,PDO::PARAM_STR);
           $sql->bindParam(":nombre",$this->nombre,PDO::PARAM_STR);
           $sql->bindParam(":foto",$this->foto,PDO::PARAM_STR);
           if($sql->execute())
           { 
            $agregado=true;

           }

        } catch (PDOException $th) {
            echo $th->getMessage();
        }
        return $agregado;
    }
    
    public static function listar_bd():array|null
    {
        $array=[];
        try {
            $pdo=new PDO("mysql:host=localhost;dbname=alumno_pdo","root","");
            $sql=$pdo->prepare("SELECT * from alumnos");
            $sql->execute();
            while($fila = $sql->fetchObject())
            {
                $a=new Alumno($fila->legajo,$fila->nombre,$fila->apellido,$fila->foto,$fila->id);
                if(isset($a))
                {
                    array_push($array,$a);
                }
            }

        } catch (PDOException $th) {
            echo $th->getMessage();
            $array=null;
        }
        return $array;
    }
    public static function obtener_bd($legajo):Alumno|null
    {
        $alumno=null;
        if(isset($legajo))
        {
            try {
                $pdo=new PDO("mysql:host=localhost;dbname=alumno_pdo","root","");
                $sql=$pdo->prepare("SELECT * from alumnos WHERE legajo=:legajo");
                $sql->bindParam(":legajo",$legajo,PDO::PARAM_INT);
                $sql->execute();
                $fila=$sql->fetchObject();
                //var_dump($fila);
                if($fila!==false)
                {
                    $alumno=new Alumno($fila->legajo,$fila->nombre,$fila->apellido,$fila->foto,$fila->id);
                }
            } catch (PDOException $th) {
                echo $th->getMessage();
            }
            
        }

        return $alumno;
    }
    
    public function modificar_bd():bool
    {
        $modificado=false;
        try {
            $pdo=new PDO("mysql:host=localhost;dbname=alumno_pdo","root","");
            $sql=$pdo->prepare("UPDATE alumnos SET apellido=:apellido,nombre=:nombre,foto=:foto WHERE legajo=:legajo");
            $sql->bindParam(":legajo",$this->legajo,PDO::PARAM_INT);
            $sql->bindParam(":apellido",$this->apellido,PDO::PARAM_STR);
            $sql->bindParam(":nombre",$this->nombre,PDO::PARAM_STR);
            $sql->bindParam(":foto",$this->foto,PDO::PARAM_STR);
            if($sql->execute())
            {
                $modificado=true;
            }
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
        return $modificado;
    }
    
    public static function borrar_bd($legajo):bool
    {
        $borrado=false;
        if(isset($legajo))
        {
            try {
                $pdo=new PDO("mysql:host=localhost;dbname=alumno_pdo","root","");
                $sql=$pdo->prepare("DELETE  from alumnos WHERE legajo=:legajo");
                $sql->bindParam(":legajo",$legajo,PDO::PARAM_INT);
                if($sql->execute())
                {
                    $borrado=true;
                }
            } catch (PDOException $th) {
                echo $th->getMessage();
            }
        }
      
        return $borrado;
    }
    public static function redirigir_db($legajo):bool
    {
    $redirigido=false;
        if(isset($legajo))
        {
            $alumno=self::obtener_bd($legajo);
            if(isset($alumno))
            {
            session_start();
                $_SESSION["alumno"]=json_encode($alumno);
                $redirigido=true;
                header("Location:./principal_bd.php");
               
            }
        }
return $redirigido;
    }

}
