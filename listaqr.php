<?php
// Incluir la conexión
include "conexion.php";
include "cabecera_pie.php";
// Incluir la biblioteca de PHP QR Code
require_once 'librerias/phpqrcode/phpqrcode.php';

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
                        WHERE MONTH(Fecha_nacimiento) >= MONTH(CURDATE()) 
                              AND DAY(Fecha_nacimiento) >= DAY(CURDATE())
                        ORDER BY MONTH(Fecha_nacimiento), DAY(Fecha_nacimiento) 
                        LIMIT 1";
$resultadoCumpleañosCercano = $cn->query($sqlCumpleañosCercano);
$rowCumpleañosCercano = $resultadoCumpleañosCercano->fetch_assoc();
$fechaCumpleañosCercano = date("d-m") . '-' . date("Y");

$gradoId = $rowCumpleañosCercano["id_gra"];
$nombreCompleto = $rowCumpleañosCercano["Apellido_paterno"] . " " . $rowCumpleañosCercano["Apellido_materno"] . " " . $rowCumpleañosCercano["Nombres"];

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
</div>

<div class="search-container">
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
            <th>QR</th> <!-- Nueva columna para mostrar el QR -->
        </tr>
    </thead>
    <tbody>
        <?php
        // Mostrar los registros
        while ($row = $resultado->fetch_assoc()) {
            
            
            // Generar el contenido del QR usando el ID del registro
            $qrContent = $row["id_datos_personales"] . " - " . $row["Apellido_paterno"]. " - " . $row["Apellido_materno"]. " - " . $row["Nombres"]. " - " . $row["Celular"]. " - " . $row["Celular"];



    
            // Generar la URL para la imagen del QR
            
            $qrImagePath = "qrcodes/" . $qrContent . ".png";

            // Generar el QR y guardarlo como imagen
            QRcode::png($qrContent, $qrImagePath, QR_ECLEVEL_L, 5, 2);

            // Mostrar el QR en la columna agregada
            echo '<tr>';
            echo '<td>' . $row["id_datos_personales"] . '</td>';
            echo '<td>' . $row["Apellido_paterno"] . '</td>';
            echo '<td>' . $row["Apellido_materno"] . '</td>';
            echo '<td>' . $row["Nombres"] . '</td>';
            echo '<td>' . $row["Celular"] . '</td>';
            echo '<td>' . $row["Fecha_nacimiento"] . '</td>';
            echo '<td><a href="mas_informacion.php?id=' . $row["id_datos_personales"] . '">Más info/Editar</a></td>';
            echo '<td><a href="#" onclick="eliminardatospersonales(' . $row["id_datos_personales"] . ')">Eliminar</a></td>';
            echo '<td><img src="' . $qrImagePath . '"></td>';
            echo '</tr>';
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
