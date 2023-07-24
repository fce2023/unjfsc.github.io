<!DOCTYPE html>
<html>
<head>
<style>
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
    background-color: #4CAF50;
    color: white;
  }

  td form {
    display: inline;
  }

  td form button {
    padding: 6px 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
  }
</style>
</head>
<body>

<?php
include("conexion.php");
include("cabecera_pie.php");

$id = $_GET["id"];

$consulta = "SELECT dp.*, c.nomcargo, cat.nomcategoria, ded.nomdedicacion, f.nomfacultad, g.nomgrado, m.nommaestria 
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
    ?>

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

    <div style="text-align: center; margin-top: 20px;">
      <form action="editar.php" method="GET">
        <input type="hidden" name="id" value="<?php echo $datosPersonales["id_datos_personales"]; ?>">
        <button type="submit">Editar</button>
      </form>
    </div>

    <?php
  } else {
    echo "No se encontraron datos personales para el ID especificado.";
  }
}
?>

</body>
</html>
