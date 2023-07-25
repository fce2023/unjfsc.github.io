<?php
// Archivo para establecer la conexión a la base de datos
include('conexion.php');

if (isset($_POST['submit']) && isset($_POST['confirmar'])) {
    // Marcar la variable "$confirmado" como true si el checkbox está marcado
    $confirmado = true;
} else {
    // Si el checkbox no está marcado o el formulario no ha sido enviado, mostrar un mensaje de advertencia
    echo "Por favor, marque la casilla de confirmación para continuar.";
    exit();
}

if ($confirmado) {
    try {
        // Sentencia SQL para borrar todos los datos de las tablas personales
        $sql1 = "DELETE FROM datospersonales";
       

        // Ejecutar las sentencias SQL
        $cn->query($sql1);
        

        echo "¡Todos los datos han sido borrados correctamente!";
    } catch (Exception $e) {
        // Manejo de errores en caso de que algo falle
        echo "Error al borrar los datos: " . $e->getMessage();
    }
}
?>
