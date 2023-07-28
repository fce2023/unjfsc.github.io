<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding('UTF-8');

include("conexion.php");
require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST["importar"])) {
    if ($_FILES["archivo"]["name"]) {
        $filename = $_FILES["archivo"]["tmp_name"];

        // Cargar el archivo Excel usando PhpSpreadsheet
        $spreadsheet = IOFactory::load($filename);
        $sheet = $spreadsheet->getActiveSheet();
        // Variable para omitir la primera fila (encabezados)
        $firstRow = true;
        // Leer los datos del archivo Excel y realizar la inserción o actualización en la tabla datospersonales
        foreach ($sheet->getRowIterator() as $row) {
            // Omitir la primera fila (encabezados)
            if ($firstRow) {
                $firstRow = false;
                continue;
            }

            $rowData = $row->getCellIterator();
            $data = array();
            foreach ($rowData as $cell) {
                $data[] = mb_convert_encoding($cell->getValue(), 'UTF-8', 'auto');
            }

            $id_datos_personales = isset($data[0]) ? $data[0] : "";
            $Apellido_paterno = isset($data[1]) ? $data[1] : "";
            $Apellido_materno = isset($data[2]) ? $data[2] : "";
            $Nombres = isset($data[3]) ? $data[3] : "";
            $Dni = isset($data[4]) ? $data[4] : "";
            $SEXO = isset($data[5]) ? $data[5] : "";
            $Fecha_nacimiento = isset($data[6]) ? $data[6] : "";
            $correo = isset($data[7]) ? $data[7] : "";
            $Celular = isset($data[8]) ? $data[8] : "";
            $Cargo = isset($data[9]) ? $data[9] : "";
            $Facultad = isset($data[10]) ? $data[10] : "";
            $Categoria = isset($data[11]) ? $data[11] : "";
            $Dedicacion = isset($data[12]) ? $data[12] : "";
            $Maestria = isset($data[13]) ? $data[13] : "";
            $Grado = isset($data[14]) ? $data[14] : "";

            // Obtener los IDs de las tablas foráneas usando los nombres
            $query_cargo = "SELECT id_cargo FROM cargo WHERE nomcargo = '$Cargo'";
            $result_cargo = mysqli_query($cn, $query_cargo);
            $id_car = mysqli_fetch_assoc($result_cargo)['id_cargo'];

            $query_facultad = "SELECT id_facultad FROM facultad WHERE nomfacultad = '$Facultad'";
            $result_facultad = mysqli_query($cn, $query_facultad);
            $id_Facu = mysqli_fetch_assoc($result_facultad)['id_facultad'];

            $query_categoria = "SELECT id_categoria FROM categoria WHERE nomcategoria = '$Categoria'";
            $result_categoria = mysqli_query($cn, $query_categoria);
            $id_cate = mysqli_fetch_assoc($result_categoria)['id_categoria'];

            $query_dedicacion = "SELECT id_dedicacion FROM dedicacion WHERE nomdedicacion = '$Dedicacion'";
            $result_dedicacion = mysqli_query($cn, $query_dedicacion);
            $id_Dedi = mysqli_fetch_assoc($result_dedicacion)['id_dedicacion'];

            $query_maestria = "SELECT id_maestria FROM maestria WHERE nommaestria = '$Maestria'";
            $result_maestria = mysqli_query($cn, $query_maestria);
            $id_maes = mysqli_fetch_assoc($result_maestria)['id_maestria'];

            $query_grado = "SELECT id_grado FROM grado WHERE nomgrado = '$Grado'";
            $result_grado = mysqli_query($cn, $query_grado);
            $id_gra = mysqli_fetch_assoc($result_grado)['id_grado'];

            // Verificar si el registro ya existe en la tabla datospersonales
            $query_select = "SELECT * FROM datospersonales WHERE id_datos_personales = '$id_datos_personales'";
            $result = mysqli_query($cn, $query_select);

            if (mysqli_num_rows($result) > 0) {
                // El registro ya existe, realizar una actualización
                $query_update = "UPDATE datospersonales SET
                    Apellido_paterno = '$Apellido_paterno',
                    Apellido_materno = '$Apellido_materno',
                    Nombres = '$Nombres',
                    Dni = '$Dni',
                    SEXO = '$SEXO',
                    Fecha_nacimiento = '$Fecha_nacimiento',
                    correo = '$correo',
                    Celular = '$Celular',
                    id_car = '$id_car',
                    id_Facu = '$id_Facu',
                    id_cate = '$id_cate',
                    id_Dedi = '$id_Dedi',
                    id_maes = '$id_maes',
                    id_gra = '$id_gra'
                    WHERE id_datos_personales = '$id_datos_personales'";

                mysqli_query($cn, $query_update);
            } else {
                // El registro no existe, realizar una inserción
                $query_insert = "INSERT INTO datospersonales (id_datos_personales, Apellido_paterno, Apellido_materno, Nombres, Dni, SEXO, Fecha_nacimiento, correo, Celular, id_car, id_Facu, id_cate, id_Dedi, id_maes, id_gra) VALUES ('$id_datos_personales', '$Apellido_paterno', '$Apellido_materno', '$Nombres', '$Dni', '$SEXO', '$Fecha_nacimiento', '$correo', '$Celular', '$id_car', '$id_Facu', '$id_cate', '$id_Dedi', '$id_maes', '$id_gra')";

                mysqli_query($cn, $query_insert);
            }
        }

        // Liberar memoria
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        // Redireccionar al índice con un mensaje de éxito
        header("Location: index.php?mensaje=Importación exitosa");
        exit;
    } else {
        // Redireccionar al índice con un mensaje de error
        header("Location: index.php?mensaje=No se seleccionó ningún archivo");
        exit;
    }
}

mysqli_close($cn);
?>
