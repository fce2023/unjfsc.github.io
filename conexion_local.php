<?php
$host = "localhost"; // Puede ser "localhost" si estás ejecutando MySQL en el mismo servidor que PHP.
$usuario = "root"; // Usuario de la base de datos.
$contrasena = ""; // Contraseña del usuario.
$base_de_datos = "fce-2023"; // Nombre de la base de datos a la que deseas conectar.

// Conexión a la base de datos
$cn = mysqli_connect($host, $usuario, $contrasena, $base_de_datos);

// Verificar si la conexión fue exitosa
if (!$cn) {
    die("La conexión ha fallado: " . mysqli_connect_error());
}

// Si la conexión fue exitosa, puedes ejecutar consultas y operaciones con la base de datos aquí.

// Cuando hayas terminado de trabajar con la base de datos, recuerda cerrar la conexión.

?>
