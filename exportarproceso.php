<?php
header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding('UTF-8');

include("conexion.php");
require_once 'librerias/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

if(isset($_POST["exportar"])) {
    // Obtener los datos de la base de datos
  $query = "SELECT dp.id_datos_personales, dp.Apellido_paterno, dp.Apellido_materno, dp.Nombres, dp.Dni, dp.SEXO, dp.Fecha_nacimiento, dp.correo, dp.Celular, c.nomcargo AS Cargo, f.nomfacultad AS Facultad, ca.nomcategoria AS Categoria, d.nomdedicacion AS Dedicacion, m.nommaestria AS Maestria, g.nomgrado AS Grado
FROM datospersonales dp
LEFT JOIN cargo c ON dp.id_car = c.id_cargo
LEFT JOIN facultad f ON dp.id_Facu = f.id_facultad
LEFT JOIN categoria ca ON dp.id_cate = ca.id_categoria
LEFT JOIN dedicacion d ON dp.id_Dedi = d.id_dedicacion
LEFT JOIN maestria m ON dp.id_maes = m.id_maestria
LEFT JOIN grado g ON dp.id_gra = g.id_grado";

    $result = mysqli_query($cn, $query);

    // Crear un nuevo objeto Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Establecer la cabecera con los nombres de las columnas
    $header = array("ID", "Apellido Paterno", "Apellido Materno", "Nombres", "DNI", "SEXO", "Fecha de Nacimiento", "Correo", "Celular", "Cargo", "Facultad", "Categoria", "Dedicacion", "Maestria", "Grado");
    $sheet->fromArray($header, NULL, 'A1');

    // Escribir los datos en las celdas correspondientes
    $rowNumber = 2;
    while($row = mysqli_fetch_assoc($result)) {
        // Convertir los valores a la codificación adecuada antes de escribirlos en el archivo Excel
        $row = array_map(function($value) {
            return mb_convert_encoding($value, 'UTF-8', 'auto');
        }, $row);

        $col = 'A';
        foreach($row as $key => $value) {
            $sheet->setCellValueExplicit($col.$rowNumber, $value, DataType::TYPE_STRING);
            $col++;
        }
        $rowNumber++;
    }

    // Configurar el formato de la hoja de cálculo
    $sheet->getStyle('A1:O1')->getFont()->setBold(true);
    $sheet->getColumnDimension('A')->setAutoSize(true);

    // Crear un objeto Writer para Excel (Xlsx)
    $writer = new Xlsx($spreadsheet);

    // Descargar el archivo Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=UTF-8');
    header('Content-Disposition: attachment;filename="datos_exportados.xlsx"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');

    // Cerrar la conexión y finalizar el script
    mysqli_close($cn);
    exit;
}
?>
