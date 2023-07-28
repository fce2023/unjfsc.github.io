<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Lista</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }
    title {

        color: blue;
    }
   
    
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    
    th, td {
      border: 1px solid #ccc;
      padding: 10px;
    }
    
    th {
      background-color: #black; /* Typo corrected from blak to black */
      color: #333;
      font-weight: cceeff;
    }
    
    td {
      background-color: #cceeff; /* Changed from #fff (white) to #cceeff (celeste, medium blue) */
      color: #333;
    }
    
    .pagination {
      margin-top: 20px;
    }
    
    .pagination a {
      display: inline-block;
      padding: 6px 12px;
      text-decoration: none;
      color: #333;
      border: 1px solid #ccc;
      background-color: #black;
    }
    
    .pagination a.current {
      background-color: #f44336;
      color: white;
    }
    
    .search-container {
      margin-top: 20px;
    }
    
    .search-container input[type="text"] {
      padding: 6px;
      width: 250px;
    }
    
    .search-container input[type="submit"] {
      padding: 6px 10px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }
    
    .delete-link {
      color: #f44336;
      text-decoration: none;
    }
    
    .delete-link:hover {
      text-decoration: underline;
    }
</style>

 
</head>
<body>

<?php
// Incluir la conexión
include "conexion.php";
include "cabecera_pie.php";

// Obtener la página actual
$paginaActual = isset($_GET["pagina"]) ? $_GET["pagina"] : 1;
$elementosPorPagina = 10;

// Calcular el inicio y fin de los registros a mostrar en la página actual
$inicio = ($paginaActual - 1) * $elementosPorPagina;
$fin = $inicio + $elementosPorPagina;

// Obtener el término de búsqueda si existe
$terminoBusqueda = isset($_GET["q"]) ? $_GET["q"] : "";

// Obtener la fecha de búsqueda si existe
$fechaBusqueda = isset($_GET["fecha"]) ? $_GET["fecha"] : "";

// Convertir la fecha de búsqueda al formato "d/m/Y" para la consulta SQL
$fechaBusquedaSQL = isset($_GET["fecha"]) ? date("Y-m-d", strtotime($_GET["fecha"])) : "";

// Modificar la consulta SQL para buscar por nombre y/o fecha
$sql = "SELECT dp.id_datos_personales, dp.Apellido_paterno, dp.Apellido_materno, dp.Nombres, dp.Celular, DATE_FORMAT(dp.Fecha_nacimiento, '%d/%m/%Y') AS Fecha_nacimiento
        FROM datospersonales dp
        WHERE (dp.Nombres LIKE '%$terminoBusqueda%' OR dp.Apellido_paterno LIKE '%$terminoBusqueda%' OR dp.DNI LIKE '%$terminoBusqueda%')
        AND (DATE_FORMAT(dp.Fecha_nacimiento, '%m-%d') = DATE_FORMAT(STR_TO_DATE('$fechaBusqueda', '%d/%m'), '%m-%d') OR '$fechaBusqueda' = '')
        LIMIT $inicio, $elementosPorPagina";
$resultado = $cn->query($sql);

// Obtener el total de registros en la tabla según el término de búsqueda
$sqlTotal = "SELECT COUNT(*) AS total FROM datospersonales WHERE (Nombres LIKE '%$terminoBusqueda%' OR Apellido_paterno LIKE '%$terminoBusqueda%' OR DNI LIKE '%$terminoBusqueda%' OR Fecha_nacimiento LIKE '%$terminoBusqueda%') AND (Fecha_nacimiento LIKE '%$fechaBusqueda%' OR '$fechaBusqueda' = '')";
$resultadoTotal = $cn->query($sqlTotal);
$filaTotal = $resultadoTotal->fetch_assoc();
$totalRegistros = $filaTotal["total"];

// Calcular el total de páginas
$totalPaginas = ceil($totalRegistros / $elementosPorPagina);

//$resultado = $cn->query($sql);

?>
<h1 style="text-align: center; font-size: 32px; color: #1E88E5; margin-top: 20px; font-weight: bold;">Lista de Docentes</h1>

<?php
$sqlCumpleañosCercano = "SELECT Fecha_nacimiento, Apellido_paterno, Apellido_materno, Nombres, id_gra
                        FROM datospersonales 
                        WHERE (MONTH(Fecha_nacimiento) > MONTH(CURDATE()) 
                               OR (MONTH(Fecha_nacimiento) = MONTH(CURDATE()) AND DAY(Fecha_nacimiento) >= DAY(CURDATE())))
                        ORDER BY MONTH(Fecha_nacimiento), DAY(Fecha_nacimiento) 
                        LIMIT 1";
$resultadoCumpleañosCercano = $cn->query($sqlCumpleañosCercano);
$rowCumpleañosCercano = $resultadoCumpleañosCercano->fetch_assoc();

if ($rowCumpleañosCercano) {
    $fechaCumpleañosCercano = date("d-m-Y", strtotime($rowCumpleañosCercano["Fecha_nacimiento"]));
    $gradoId = $rowCumpleañosCercano["id_gra"];
    $nombreCompleto = $rowCumpleañosCercano["Apellido_paterno"] . " " . $rowCumpleañosCercano["Apellido_materno"] . " " . $rowCumpleañosCercano["Nombres"];
} else {
    $fechaCumpleañosCercano = "";
    $gradoId = "";
    $nombreCompleto = "";
}



// Función para obtener la abreviatura del grado
function obtenerAbreviaturaGrado($gradoId)
{
    switch ($gradoId) {
        case 0:
            return '';
        case 1:
            return 'DC.';
        case 2:
            return 'MTR.';
        case 3:
            return 'LC.';
        default:
            return '';
    }
}

$abreviaturaGrado = obtenerAbreviaturaGrado($gradoId);
?>

<label id="fechaCumpleanosLabel" style="font-size: 18px; color: #333; margin-top: 20px;">
    Fecha de Cumpleaños más cercana: <?php echo $fechaCumpleañosCercano; ?>
</label>

<br>

<label id="nombreCompletoLabel" style="font-size: 18px; color: #333; margin-top: 10px;">
    Nombre: <?php echo $abreviaturaGrado . ' ' . $nombreCompleto; ?>
</label>






   <div class="search-container">
    <form action="" method="GET">
        <input type="text" name="q" placeholder="Buscar por apellido, DNI, fecha de nacimiento" value="<?php echo $terminoBusqueda; ?>">
        <input type="submit" value="Buscar">
    </form>

    <form action="" method="GET">
        <input type="text" name="fecha" placeholder="Buscar por día y mes (dd/mm)" value="<?php echo isset($_GET['fecha']) ? $_GET['fecha'] : ''; ?>">
        <input type="submit" value="Buscar por fecha">
    </form>
</div>



<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Nombres</th>
            <th>Celular</th>
            <th>Fecha_nacimiento</th>
            <th>Más info/Editar</th>
            <th>Eliminar</th>
            
        </tr>
    </thead>
    <tbody>
        <?php
        // Mostrar los registros
        while ($row = $resultado->fetch_assoc()) {
            ?>
            <tr>
                <td><?php echo $row["id_datos_personales"]; ?></td>
                <td><?php echo $row["Apellido_paterno"]; ?></td>
                <td><?php echo $row["Apellido_materno"]; ?></td>
                <td><?php echo $row["Nombres"]; ?></td>
                <td><?php echo $row["Celular"]; ?></td>
                <td><?php echo $row["Fecha_nacimiento"]; ?></td>
                <td><a href="mas_informacion.php?id=<?php echo $row["id_datos_personales"]; ?>">Más info/Editar</a></td>
                <td><a href="#" onclick="eliminardatospersonales(<?php echo $row["id_datos_personales"]; ?>)">Eliminar</a></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>

<div class="pagination">
    <?php
    // Generar los enlaces de paginación
    for ($i = 1; $i <= $totalPaginas; $i++) {
        $enlace = "?pagina=" . $i;
        if (!empty($terminoBusqueda)) {
            $enlace .= "&q=$terminoBusqueda";
        }
        if ($i == $paginaActual) {
            $enlace = "<a class='current' href='$enlace'>$i</a>";
        } else {
            $enlace = "<a href='$enlace'>$i</a>";
        }
        echo $enlace;
    }
    ?>
</div>

<?php
// Cerrar la conexión con la base de datos
$cn->close();
?>

<script>
    function eliminardatospersonales(iddatospersonales) {
        // Confirma con el usuario antes de eliminar el registro
        if (confirm("¿Estás seguro de eliminar este Docente?")) {
            // Realiza la solicitud AJAX para eliminar el registro
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Muestra la respuesta del servidor en la consola
                    console.log(this.responseText);

                    // Actualiza la página después de eliminar el registro
                    location.reload();
                }
            };
            xhttp.open("POST", "eliminar.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("iddatospersonales=" + iddatospersonales);
        }
    }
</script>

</body>
</html>
