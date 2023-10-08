<?php

use Mpdf\HTMLParserMode;

require_once __DIR__."/vendor/autoload.php";


$contenido=file_get_contents("./archivos/alumnos_foto.txt");

$css=file_get_contents("./style_pdf.css");
$filas=explode("\r\n",$contenido);
$array=[];
foreach ($filas as $key => $value) {
    $alum_array=explode("-",$value);
    $std= new stdClass();
    if((isset($alum_array[0])) && !empty($alum_array[0]))
    {

        $std->legajo=$alum_array[0];
        $std->nombre=$alum_array[1];        
        $std->apellido=$alum_array[2];
        $std->foto=$alum_array[3];

        array_push($array,$std);
    }


}
$grilla='<table>
<thead>
    <tr ><td>LEGAJO</td> 
    <td>NOMBRE </td>
     <td>APELLIDO</td>
      <td>IMAGEN</td></tr>
</thead>
<tbody>';
foreach ($array as $key => $value) {
    if(isset($value))
    {
        $grilla.='
        <tr><td>'.$value->legajo.'</td><td>'.$value->nombre.'</td><td>'.$value->apellido.'</td><td>
        <img src="./fotos/'.$value->foto.'" alt="foto alumno" width="50px" height="50px"></td></tr>';
    }
}
$grilla.='</tbody>
</table>';
header('content-type:application/pdf');


$mpdf = new \Mpdf\Mpdf(['orientation' => 'P', 
                'pagenumPrefix' => 'Nro. ',
                'pagenumSuffix' => ' - ',
                'nbpgPrefix' => ' de ',
                'nbpgSuffix' => ' páginas']);
                
$mpdf->SetHeader('Gisele Medina 3A||{PAGENO}{nbpg}');
$mpdf->SetFooter('|{DATE j-m-Y}|');

$mpdf->SetWatermarkText('3 A', 0.1);
$mpdf->showWatermarkText = true;
$mpdf->watermark_font = 'Ariel';

$mpdf->writeHTML($css,HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML("<h1>LISTAR PDF</h1>");
$mpdf->WriteHTML($grilla);
/**$mpdf->SetProtection(array('copy'), 'UserPassword', 'MyPassword');
//El usuario, solo tendrá permiso de copia. El propietario, acceso completo


//permisos
// 'copy'
// 'print'
// 'modify'
// 'annot-forms'
// 'fill-forms'
// 'extract'
// 'assemble'
// 'print-highres' */

//$mpdf->SetProtection(array(),"3A","parcial");
$mpdf->Output("Medina/listar.pdf","I");