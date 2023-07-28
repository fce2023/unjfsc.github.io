<?php
include("conexion.php");
include("cabecera_pie.php");

$id = $_GET["id"];

$consulta = "SELECT dp.*, c.nomcargo, cat.nomcategoria, ded.nomdedicacion, f.nomfacultad, g.nomgrado, m.nommaestria, 
             CONCAT(dp.Apellido_paterno, ' ', dp.Apellido_materno, ' ', dp.Nombres) AS nombre_completo
             FROM datospersonales dp
             INNER JOIN cargo c ON dp.id_car = c.id_cargo
             INNER JOIN categoria cat ON dp.id_cate = cat.id_categoria
             INNER JOIN dedicacion ded ON dp.id_Dedi = ded.id_dedicacion
             INNER JOIN facultad f ON dp.id_Facu = f.id_facultad
             INNER JOIN grado g ON dp.id_gra = g.id_grado
             INNER JOIN maestria m ON dp.id_maes = m.id_maestria
             WHERE dp.id_datos_personales = $id";
$resultado = $cn->query($consulta);

if (!$resultado) {
  echo "Error en la consulta: " . $cn->error;
} else {
  if ($resultado->num_rows > 0) {
    $datosPersonales = $resultado->fetch_assoc();
    $nombre_completo = $datosPersonales['nombre_completo'];
  }
}
?>

<!DOCTYPE html>
<html>
<head>
<style>
  body {
    font-family: Arial, sans-serif;
  }

  /* Estilos de tabla general */
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }

  table, th, td {
    border: 1px solid black;
    padding: 8px;
  }

  th {
    background-color: #1E88E5;
    color: white;
  }

  /* Estilos de la sección de fotos */
  .foto-container {
    text-align: left ;
    margin-top: 15px;
    float: left;
  }

  #imagenPrevia {
    max-width: 200px;
    float: left; /* Establece el flotador a la izquierda */
    margin-right: 20px; /* Espacio entre el contenedor y otros elementos */
  }
  }

  /* Estilos de la sección de carga de fotos */
  fieldset {
    float: left; /* Establece el flotador a la izquierda */
    
    padding: 10px;
  }

  fieldset legend {
    font-size: 18px;
    float: left
  }

  fieldset div {
    text-align: center;
    margin-top: 10px;
    float: left
  }

  fieldset input[type="file"] {
    margin-top: 10px;
    float: left
  }

  fieldset button[type="submit"] {
    padding: 8px 15px;
    background-color: #1E88E5;
    color: white;
    border: none;
    cursor: pointer;
    float: left
  }
  .contenedor-botones {
    float: left; /* Establece el flotador a la izquierda */
    margin-right: 20px; /* Espacio entre el contenedor y otros elementos */
  }
</style>
</head>
<body>
  <?php
  if (isset($datosPersonales)) {
    // Código HTML para mostrar los datos
    ?>
    <fieldset>
      <legend>Datos Personales</legend>
      <table>
        <h2>Datos Personales</h2>
        <table>
          <tr>
            <th>ID</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Nombres</th>
            <th>DNI</th>
            <th>Sexo</th>
            <th>Fecha de Nacimiento</th>
            <th>Correo</th>
            <th>Celular</th>
            <th>Cargo</th>
            <th>Categoría</th>
            <th>Dedicación</th>
            <th>Facultad</th>
            <th>Grado</th>
            <th>Maestría</th>
          </tr>
          <tr>
            <td><?php echo $datosPersonales["id_datos_personales"]; ?></td>
            <td><?php echo $datosPersonales["Apellido_paterno"]; ?></td>
            <td><?php echo $datosPersonales["Apellido_materno"]; ?></td>
            <td><?php echo $datosPersonales["Nombres"]; ?></td>
            <td><?php echo $datosPersonales["Dni"]; ?></td>
            <td><?php echo $datosPersonales["SEXO"]; ?></td>
            <td><?php echo date('d-m-Y', strtotime($datosPersonales["Fecha_nacimiento"])); ?></td>
            <td><?php echo $datosPersonales["correo"]; ?></td>
            <td><?php echo $datosPersonales["Celular"]; ?></td>
            <td><?php echo $datosPersonales["nomcargo"]; ?></td>
            <td><?php echo $datosPersonales["nomcategoria"]; ?></td>
            <td><?php echo $datosPersonales["nomdedicacion"]; ?></td>
            <td><?php echo $datosPersonales["nomfacultad"]; ?></td>
            <td><?php echo $datosPersonales["nomgrado"]; ?></td>
            <td><?php echo $datosPersonales["nommaestria"]; ?></td>
          </tr>
        </table>
      </table>
    </fieldset>

    <fieldset>
      <legend>Foto</legend>
      <div class="foto-container">
        <h2></h2>
        <div id="vistaPrevia">
          <img id="imagenPrevia" src="#" alt="Vista previa de la foto" style="display: none;">
        </div>
      </div>

      <?php
      // Cargar la biblioteca de Google API Client desde la carpeta raíz con el nombre 'google'
      require 'vendor/autoload.php';

      // Reemplazar 'TU_NOMBRE_DE_PROYECTO' con el nombre de tu proyecto en Google Developer Console
      $folderId = "1sRxTB0PVf7qLsiMS9ss6b7tCOi4NrbzG";
      $credentialsPath = 'credenciales_google_drive.json'; // Ruta al archivo de credenciales JSON

      // Cargar las credenciales
      $credentials = json_decode(file_get_contents($credentialsPath), true);

      // Reemplaza 'TU_NOMBRE_DE_PROYECTO' con el nombre de tu proyecto en Google Developer Console
      putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $credentialsPath);
      $client = new Google\Client();
      $client->useApplicationDefaultCredentials();
      $client->setScopes(Google\Service\Drive::DRIVE);

      // Crear el servicio de Google Drive
      $service = new Google\Service\Drive($client);

      // Obtener el nombre de la foto con extensión
      $nombre_foto = $id . '_' . $nombre_completo . '.jpg'; // Cambiar la extensión si es diferente

      // Buscar si ya existe una foto con el mismo nombre en la carpeta de destino
      $existingFiles = $service->files->listFiles(array(
          'q' => "'$folderId' in parents and name='$nombre_foto'",
          'fields' => 'files(id)'
      ));

      if (count($existingFiles->getFiles()) > 0) {
          // Mostrar la foto utilizando una etiqueta de imagen con el enlace de Google Drive
          $photoUrl = "https://drive.google.com/uc?id=" . $existingFiles->getFiles()[0]->getId();

          // También mostramos la vista previa de la foto en el tamaño especificado
          echo "<script>document.getElementById('imagenPrevia').src = '$photoUrl'; document.getElementById('imagenPrevia').style.display = 'inline';</script>";
      } else {
          echo "Aun no a registrado una foto subir Foto";
      }
      ?>

      <div style="text-align: center; margin-top: 20px;">
        <form action="editar.php" method="GET">
          <input type="hidden" name="id" value="<?php echo $datosPersonales["id_datos_personales"]; ?>">
          <div class="contenedor-botones">
  <button type="submit">Editar Datos</button>
</div>
        </form>
      </div>
    </fieldset>

    <fieldset  id = "fotos_cargar" >
      <legend>Subir o Actualizar Foto</legend>
      <div>
        <form action="proceso_actualizar_foto.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?php echo $datosPersonales["id_datos_personales"]; ?>">
          <input type="file" name="photo" accept=".jpg, .jpeg, .png" required>
          <button type="submit">Subir Foto</button>
        </form>
      </div>
    </fieldset>
  <?php
  } else {
    echo "No se encontraron datos personales para el ID especificado.";
  }
  ?>

</body>
</html>
