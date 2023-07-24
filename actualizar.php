<?php
include("conexion.php");

// Verifica si el ID está presente en la solicitud POST
if (!isset($_POST['id_datos_personales'])) {
    echo "ID de datos personales no proporcionado.";
    exit;
}

$id_datos_personales = $_POST['id_datos_personales'];

$txtpaterno = $_POST['txtpaterno'];
$txtmaterno = $_POST['txtmaterno'];
$txtnombre = $_POST['txtnombre'];
$txtdni = $_POST['txtdni'];
$sexo = $_POST['sexo'];
$correo = $_POST['correo'];
$celular = $_POST['celular'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];

$id_car = $_POST['cargo'];
$id_facu = $_POST['facultad'];
$id_cate = $_POST['categoria'];
$id_dedi = $_POST['dedicacion'];
$id_maes = $_POST['maestria'];
$id_gra = $_POST['grado'];

// Actualizar los datos en la tabla
$update_query = "UPDATE datospersonales
                SET Apellido_paterno = '$txtpaterno',
                    Apellido_materno = '$txtmaterno',
                    Nombres = '$txtnombre',
                    Dni = '$txtdni',
                    SEXO = '$sexo',
                    Fecha_nacimiento = '$fecha_nacimiento',
                    correo = '$correo',
                    Celular = '$celular',
                    id_car = '$id_car',
                    id_Facu = '$id_facu',
                    id_cate = '$id_cate',
                    id_Dedi = '$id_dedi',
                    id_maes = '$id_maes',
                    id_gra = '$id_gra'
                WHERE id_datos_personales = $id_datos_personales";

if (mysqli_query($cn, $update_query)) {
    mysqli_close($cn);
    echo "<script>alert('Actualización exitosa.'); window.location.href='lista.php';</script>";
    exit;
} else {
    echo "Error al actualizar los datos personales: " . mysqli_error($cn);
}

mysqli_close($cn);
?>
