<?php
// procesoagregarfoto.php

require 'vendor/autoload.php'; // Cargar la biblioteca de Google API Client desde la carpeta raíz con el nombre 'google'

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['btn_si'])) {
        // User selected "Guardar sin foto"
        header("Location: index.php");
        exit();
    } elseif (isset($_POST['btn_agregar_foto'])) {
        // Verificar que se haya enviado el ID de la persona y que exista la foto
        if (isset($_POST['id_persona']) && isset($_FILES['foto'])) {
            $id_persona = $_POST['id_persona'];
            $foto = $_FILES['foto'];

            // Ruta al archivo de credenciales JSON
            $credentialsPath = 'credenciales_google_drive.json';

            // Cargar las credenciales
            $credentials = json_decode(file_get_contents($credentialsPath), true);

            // Reemplaza 'TU_NOMBRE_DE_PROYECTO' con el nombre de tu proyecto en Google Developer Console
            putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $credentialsPath);
            $client = new Google\Client();
            $client->useApplicationDefaultCredentials();
            $client->setScopes(Drive::DRIVE);

            // Crear el servicio de Google Drive
            $service = new Drive($client);

            // ID de la carpeta de destino para subir la foto
            $folderId = "1sRxTB0PVf7qLsiMS9ss6b7tCOi4NrbzG";

            // Obtener el nombre de la foto usando el nombre completo de la persona
            require 'conexion.php'; // Asegúrate de tener la conexión a la base de datos en este archivo

            $sql = "SELECT Apellido_paterno, Apellido_materno, Nombres FROM datospersonales WHERE id_datos_personales = $id_persona";
            $result = $cn->query($sql);

            if ($result->num_rows > 0) {
                $fila = $result->fetch_assoc();
                $nombre_completo = $fila['Apellido_paterno'] . ' ' . $fila['Apellido_materno'] . ' ' . $fila['Nombres'];
            } else {
                // Si no se encuentra el ID en la base de datos, mostrar un mensaje de error o redirigir a donde corresponda
                die("ID de persona no encontrado en la base de datos.");
            }

            // Obtener el nombre de la foto con extensión
            $nombre_foto = $id_persona . '_' . $nombre_completo . '.' . pathinfo($foto['name'], PATHINFO_EXTENSION);

            // Buscar si ya existe una foto con el mismo nombre en la carpeta de destino
            $existingFiles = $service->files->listFiles(array(
                'q' => "'$folderId' in parents and name='$nombre_foto'",
                'fields' => 'files(id)'
            ));

            if (count($existingFiles->getFiles()) > 0) {
                // Eliminar la foto existente antes de subir la nueva
                $existingFileId = $existingFiles->getFiles()[0]->getId();
                $service->files->delete($existingFileId);
            }

            // Subir el archivo a Google Drive en la carpeta de destino
            $fileMetadata = new DriveFile(array(
                'name' => $nombre_foto,
                'parents' => array($folderId)
            ));

            $result = $service->files->create($fileMetadata, array(
                'data' => file_get_contents($foto['tmp_name']),
                'mimeType' => $foto['type'],
                'uploadType' => 'multipart'
            ));

            // Redirigir a index.php
            header("Location: index.php");
            exit();
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