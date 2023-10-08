<?php

require_once "./alumno.php";

use Medina\Alumno;

session_start();
if (isset($_SESSION["alumno"])) {
    //   var_dump($_SESSION["alumno"]);
    $std = json_decode($_SESSION["alumno"]);
    // var_dump($std);
    if (isset($std)) {
        $alumno = new Alumno($std->legajo, $std->nombre, $std->apellido, $std->foto);
        //var_dump($alumno);
        $a=Alumno::obtener_bd($alumno->legajo);
        if(!isset($a))
        {

            header("Localtion:./nexo_pdo.php");
            die();
        } else {


            $alumnos = Alumno::listar_bd();
       
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal php</title>

</head>

<body>
    <h1> <?php echo $alumno->legajo ?> </h1>
    <h2> <?php echo $alumno->nombre . ", $alumno->apellido" ?></h2>
    <img src="./fotos/<?php echo $alumno->foto ?>" alt="foto alumno" width="50px" height="50px">
    <table>
        <thead>

            <tr>
                <th>LEGAJO</th>
                <th>NOMBRE</th>
                <th>APELLIDO</th>
                <th>IMAGEN</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($alumnos as $key => $value) {
                if (isset($value)) {
                    echo "<tr><td>$value->legajo </td><td>$value->nombre </td><td>$value->apellido</td>" . '<td> <img src="./fotos/' . $value->foto . ' " alt="foto alumno" width="50px" height="50px"></td></tr>';
                }
            }
            ?>
        </tbody>
    </table>
</body>

</html>;