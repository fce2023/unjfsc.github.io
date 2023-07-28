<?php
// Incluir la conexión
include "conexion.php";

// Cargar la biblioteca de Google API Client desde la carpeta raíz con el nombre 'google'
require 'vendor/autoload.php';

use Google\Client;
use Google\Service\Drive;

// Verificar si se recibió el parámetro "iddatospersonales" por POST
if (isset($_POST["iddatospersonales"])) {
    // Obtener el ID del registro a eliminar
    $id = $_POST["iddatospersonales"];

    // Ruta al archivo de credenciales JSON
    $credentialsPath = 'credenciales_google_drive.json';

    // Cargar las credenciales
    $credentials = json_decode(file_get_contents($credentialsPath), true);

    // Crear el cliente de Google y configurar las credenciales
    $client = new Google\Client();
    $client->setAuthConfig($credentialsPath);
    $client->addScope(Google_Service_Drive::DRIVE);

    // Obtener el token de acceso
    $accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];
    $client->setAccessToken($accessToken);

    // Crear el servicio de Google Drive
    $service = new Google_Service_Drive($client);

    // ID de la carpeta en Google Drive donde se encuentran las fotos
    $folderId = "1sRxTB0PVf7qLsiMS9ss6b7tCOi4NrbzG";

    // Obtener el nombre del archivo de la foto asociada a este registro
    $sql = "SELECT Apellido_paterno, Apellido_materno, Nombres FROM datospersonales WHERE id_datos_personales = $id";
    $result = $cn->query($sql);

    if ($result->num_rows > 0) {
        // Obtener los datos del registro
        $fila = $result->fetch_assoc();
        $nombre_completo = $fila['Apellido_paterno'] . ' ' . $fila['Apellido_materno'] . ' ' . $fila['Nombres'];

        // Generar el nombre del archivo de la foto asociada al registro
        $nombreArchivoFoto = $id . "_" . str_replace(' ', '_', $nombre_completo) . ".jpg";

        // Buscar todas las fotos en la carpeta de destino
        $existingFiles = $service->files->listFiles(array(
            'q' => "'$folderId' in parents",
            'fields' => 'files(id, name)'
        ));

        // Iterar sobre los archivos y eliminar tanto el registro como la foto asociada si hay coincidencia
        foreach ($existingFiles->getFiles() as $file) {
            // Verificar si el nombre del archivo contiene el ID del registro actual
            if (strpos($file->getName(), $id) !== false) {
                // Eliminar la foto en Google Drive
                $fileId = $file->getId();
                $service->files->delete($fileId);

                // Eliminar el registro de la tabla "datospersonales"
                $sqlEliminar = "DELETE FROM datospersonales WHERE id_datos_personales = $id";
                if ($cn->query($sqlEliminar)) {
                    // Actualizar los IDs de los registros restantes para mantener la continuidad
                    $update_ids_query = "SET @count = 0";
                    mysqli_query($cn, $update_ids_query);
                    $update_ids_query = "UPDATE datospersonales SET datospersonales.id_datos_personales = @count:= @count + 1";
                    mysqli_query($cn, $update_ids_query);

                    // Cerrar la conexión con la base de datos
                    $cn->close();

                    // Redireccionar a lista.php con mensaje de éxito
                    header("Location: lista.php?eliminado=1");
                    exit();
                } else {
                    echo "Error al eliminar el registro: " . $cn->error;
                }
            }
        }
        echo "No se encontró ninguna foto asociada al registro.";
    } else {
        echo "Registro no encontrado.";
    }
}
?>
