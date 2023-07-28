<?php
// upload_photo.php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar que se haya enviado el ID y la foto
    if (isset($_POST["id"]) && isset($_FILES["photo"])) {
        $id = $_POST["id"];

        // Obtener información de la foto subida
        $photo = $_FILES["photo"];
        $photoName = $photo["name"];
        $photoTmpName = $photo["tmp_name"];
        $photoError = $photo["error"];

        // Verificar si se produjo algún error al subir la foto
        if ($photoError !== UPLOAD_ERR_OK) {
            echo "Error al subir la foto: " . $photoError;
            exit;
        }

        // Cargar la biblioteca de Google API Client desde la carpeta raíz con el nombre 'google'
        require 'vendor/autoload.php';

        // Ruta al archivo de credenciales JSON
        $credentialsPath = 'credenciales_google_drive.json';

        // Cargar las credenciales
        $credentials = json_decode(file_get_contents($credentialsPath), true);

        // Reemplazar 'TU_NOMBRE_DE_PROYECTO' con el nombre de tu proyecto en Google Developer Console
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $credentialsPath);
        $client = new Google\Client();
        $client->useApplicationDefaultCredentials();
        $client->setScopes(Google\Service\Drive::DRIVE);

        // Crear el servicio de Google Drive
        $service = new Google\Service\Drive($client);

        // ID de la carpeta de destino para subir la foto
        $folderId = "1sRxTB0PVf7qLsiMS9ss6b7tCOi4NrbzG";

        // Obtener el nombre de la foto usando el ID y el nombre de la persona
        require 'conexion.php'; // Asegúrate de tener la conexión a la base de datos en este archivo

        $sql = "SELECT Apellido_paterno, Apellido_materno, Nombres FROM datospersonales WHERE id_datos_personales = $id";
        $result = $cn->query($sql);

        if ($result->num_rows > 0) {
            $fila = $result->fetch_assoc();
            $nombre_completo = $fila['Apellido_paterno'] . ' ' . $fila['Apellido_materno'] . ' ' . $fila['Nombres'];
        } else {
            // Si no se encuentra el ID en la base de datos, mostrar un mensaje de error o redirigir a donde corresponda
            die("ID de persona no encontrado en la base de datos.");
        }

        // Obtener el nombre de la foto con extensión
        $nombre_foto = $id . '_' . $nombre_completo . '.' . pathinfo($photoName, PATHINFO_EXTENSION);

        // Buscar si ya existe una foto con el mismo nombre en la carpeta de destino
        $existingFiles = $service->files->listFiles(array(
            'q' => "'$folderId' in parents and name='$nombre_foto'",
            'fields' => 'files(id)'
        ));

        if (count($existingFiles->getFiles()) > 0) {
            // Si ya existe una foto con el mismo nombre, eliminarla antes de subir la nueva
            $existingFileId = $existingFiles->getFiles()[0]->getId();
            $service->files->delete($existingFileId);
        }

        // Ruta completa donde se almacenará la foto en Google Drive
        $photoPath = "/ruta/de/carpeta/en/google/drive/$nombre_foto";

        // Subir la foto a Google Drive
        try {
            $fileMetadata = new Google\Service\Drive\DriveFile(array(
                'name' => $nombre_foto,
                'parents' => array($folderId)
            ));

            $content = file_get_contents($photoTmpName);
            $file = $service->files->create($fileMetadata, array(
                'data' => $content,
                'mimeType' => 'image/jpeg', // Cambiar el tipo MIME si es necesario
                'uploadType' => 'multipart'
            ));

            // Si se subió correctamente, mostrar un mensaje y redirigir a mas_informacion.php
            echo "Foto actualizada con éxito.";
            echo "<script>alert('Foto actualizada'); window.location.href = 'lista.php';</script>";
        } catch (Exception $e) {
            // Si se produce un error, mostrar un mensaje de error
            echo "Error al actualizar la foto en Google Drive: " . $e->getMessage();
        }
    } else {
        echo "ID de datos personales o foto no especificados.";
    }
} else {
    echo "Acceso no permitido.";
}
?>
