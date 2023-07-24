<?php
include("conexion.php");

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

// Reiniciar el contador de ID a 1
$alter_query = "ALTER TABLE datospersonales AUTO_INCREMENT = 1";
mysqli_query($cn, $alter_query);

// Insertar los datos en la tabla
$insert_query = "INSERT INTO datospersonales (Apellido_paterno, Apellido_materno, Nombres, Dni, SEXO, Fecha_nacimiento, correo, Celular, id_car, id_Facu, id_cate, id_Dedi, id_maes, id_gra)
                VALUES ('$txtpaterno', '$txtmaterno', '$txtnombre', '$txtdni', '$sexo', '$fecha_nacimiento', '$correo', '$celular', '$id_car', '$id_facu', '$id_cate', '$id_dedi', '$id_maes', '$id_gra')";

if (mysqli_query($cn, $insert_query)) {
    mysqli_close($cn);
    echo "<script>; window.location.href='opciones.php';</script>";
    exit;
} else {
    echo "Error al insertar los datos personales: " . mysqli_error($cn);
}

// Actualiza los IDs de los registros
$update_ids_query = "SET @count = 0";
mysqli_query($cn, $update_ids_query);

$update_ids_query = "UPDATE datospersonales SET id_datos_personales = @count:= @count + 1";
mysqli_query($cn, $update_ids_query);

mysqli_close($cn);
?>
