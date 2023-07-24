<?php
header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding('UTF-8');

include("conexion.php");
require_once 'librerias/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if(isset($_POST["importar"])) {
    // Verificar si se ha seleccionado un archivo para importar
    if(isset($_FILES['archivo']) && $_FILES['archivo']['error'] == UPLOAD_ERR_OK) {
        $archivo = $_FILES['archivo']['tmp_name'];

        // Cargar el archivo Excel y obtener los datos
        $spreadsheet = IOFactory::load($archivo);
        $worksheet = $spreadsheet->getActiveSheet();
        $datos = $worksheet->toArray();

        // Eliminar la primera fila (encabezados)
        array_shift($datos);

        // Insertar los datos en la base de datos
        foreach ($datos as $fila) {
            $apellidoPaterno = $fila[0];
            $apellidoMaterno = $fila[1];
            $nombres = $fila[2];
            $dni = $fila[3];
            $sexo = $fila[4];
            $fechaNacimiento = $fila[5];
            $correo = $fila[6];
            $celular = $fila[7];
            $cargo = $fila[8];
            $facultad = $fila[9];
            $categoria = $fila[10];
            $dedicacion = $fila[11];
            $maestria = $fila[12];
            $grado = $fila[13];

            // Verificar el formato de la fecha de nacimiento
            $fechaNacimientoObj = DateTime::createFromFormat('d/m/Y', $fechaNacimiento);
            if ($fechaNacimientoObj === false) {
                echo "Error: Formato de fecha de nacimiento inválido";
                continue; // Pasar a la siguiente iteración del bucle
            }

            // Obtener la fecha de nacimiento en el formato adecuado
            $fechaNacimiento = $fechaNacimientoObj->format('Y-m-d');

            // Realizar las consultas para obtener los IDs correspondientes a los nombres
            $cargoQuery = "SELECT id_cargo FROM cargo WHERE nomcargo = '$cargo'";
            $facultadQuery = "SELECT id_facultad FROM facultad WHERE nomfacultad = '$facultad'";
            $categoriaQuery = "SELECT id_categoria FROM categoria WHERE nomcategoria = '$categoria'";
            $dedicacionQuery = "SELECT id_dedicacion FROM dedicacion WHERE nomdedicacion = '$dedicacion'";
            $maestriaQuery = "SELECT id_maestria FROM maestria WHERE nommaestria = '$maestria'";
            $gradoQuery = "SELECT id_grado FROM grado WHERE nomgrado = '$grado'";

            $cargoResult = mysqli_query($cn, $cargoQuery);
            $facultadResult = mysqli_query($cn, $facultadQuery);
            $categoriaResult= mysqli_query($cn, $categoriaQuery);
            $dedicacionResult = mysqli_query($cn, $dedicacionQuery);
            $maestriaResult = mysqli_query($cn, $maestriaQuery);
            $gradoResult = mysqli_query($cn, $gradoQuery);

            // Obtener los IDs correspondientes a los nombres
            $idCargo = ($cargoRow = mysqli_fetch_assoc($cargoResult)) ? $cargoRow['id_cargo'] :```php
0;
            $idFacultad = ($facultadRow = mysqli_fetch_assoc($facultadResult)) ? $facultadRow['id_facultad'] : 0;
            $idCategoria = ($categoriaRow = mysqli_fetch_assoc($categoriaResult)) ? $categoriaRow['id_categoria'] : 0;
            $idDedicacion = ($dedicacionRow = mysqli_fetch_assoc($dedicacionResult)) ? $dedicacionRow['id_dedicacion'] : 0;
            $idMaestria = ($maestriaRow = mysqli_fetch_assoc($maestriaResult)) ? $maestriaRow['id_maestria'] : 0;
            $idGrado = ($gradoRow = mysqli_fetch_assoc($gradoResult)) ? $gradoRow['id_grado'] : 0;

            // Insertar los datos en la tabla datospersonales
            $sql = "INSERT INTO datospersonales (Apellido_paterno, Apellido_materno, Nombres, Dni, SEXO, Fecha_nacimiento, correo, Celular, id_car, id_Facu, id_cate, id_Dedi, id_maes, id_gra) 
                    VALUES ('$apellidoPaterno', '$apellidoMaterno', '$nombres', '$dni', '$sexo', '$fechaNacimiento', '$correo', '$celular', '$idCargo', '$idFacultad', '$idCategoria', '$idDedicacion', '$idMaestria', '$idGrado')";

            if (mysqli_query($cn, $sql)) {
                echo "Datos insertados correctamente";
            } else {
                echo "Error al insertar los datos: " . mysqli_error($cn);
            }
        }

        // Cerrar la conexión a la base de datos
        mysqli_close($cn);
    } else {
        echo "Error al cargar el archivo";
    }
}
?>
