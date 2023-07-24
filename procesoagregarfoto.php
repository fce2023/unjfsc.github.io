<?php
// procesoagregarfoto.php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['btn_si'])) {
        // User selected "Guardar sin foto"
        header("Location: index.php");
        exit();
    } elseif (isset($_POST['btn_agregar_foto'])) {
        // Verificar que se haya enviado la foto y el ID de la persona
        if (isset($_POST['id_persona']) && isset($_FILES['foto'])) {
            $id_persona = $_POST['id_persona'];
            $foto = $_FILES['foto'];

            // Obtener la extensión de la foto
            $extension = pathinfo($foto['name'], PATHINFO_EXTENSION);

            // Crear el nombre de la foto con el formato "ID_nombre.ext"
            $nombre_foto = $id_persona . '_' . "nombre" . '.' . $extension;

            // Ruta donde se guardará la foto (en este ejemplo, la carpeta "fotos")
            $ruta_destino = 'fotos/' . $nombre_foto;

            // Mover la foto del directorio temporal al directorio final
            if (move_uploaded_file($foto['tmp_name'], $ruta_destino)) {
                // La foto se ha guardado correctamente
                header("Location: index.php");
            } else {
                // Error al guardar la foto
                echo "Ha ocurrido un error al guardar la foto.";
            }
        } else {
            // Datos faltantes, redirigir al formulario con mensaje de error
            header("Location: agregarfoto.php?error=1");
            exit;
        }
    }
} else {
    // Redirigir al formulario si se intenta acceder directamente a este archivo sin enviar el formulario
    header("Location: agregarfoto.php");
    exit;
}
?>
