<?php
// Incluir la conexión
include "conexion.php";

// Verificar si se recibió el parámetro "iddatospersonales" por POST
if (isset($_POST["iddatospersonales"])) {
    // Obtener el ID del registro a eliminar
    $id = $_POST["iddatospersonales"];

    // Obtener el nombre del archivo de la foto asociada a este registro
    $nombreArchivoFoto = $id . "_nombre.jpg";

    // Eliminar el archivo físico de la foto de la carpeta "fotos"
    $rutaArchivoFoto = "fotos/" . $nombreArchivoFoto;
    if (file_exists($rutaArchivoFoto)) {
        unlink($rutaArchivoFoto);
    }

    // Eliminar el registro de la tabla "datospersonales"
    $sqlEliminar = "DELETE FROM datospersonales WHERE id_datos_personales = $id";
    if ($cn->query($sqlEliminar)) {
        // Mostrar mensaje de éxito con JavaScript
        echo "<script>alert('Registro eliminado correctamente.'); window.location.href='lista.php';</script>";
        exit();
        
        // Actualizar el contador del ID
        $sqlActualizarID = "ALTER TABLE datospersonales AUTO_INCREMENT = $id";
        $cn->query($sqlActualizarID);

        // Cerrar la conexión con la base de datos
        $cn->close();

        // Redireccionar a lista.php con mensaje de éxito
        header("Location: lista.php?eliminado=1");
        exit();
    } else {
        echo "Error al eliminar el registro: " . $cn->error;
    }
}
?>


