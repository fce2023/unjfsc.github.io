<?php
include("conexion.php");

function calcularSiguienteFechaCumple($cn, $fechaReferencia, $anterior = false)
{
    // Obtener el día y mes de la fecha de referencia
    $mesDiaReferencia = substr($fechaReferencia, 5);

    // Obtener la fecha actual
    $fechaActual = date('Y-m-d');

    // Obtener el año actual
    $añoActual = date('Y');

    // Calcular la siguiente fecha de cumpleaños o la fecha anterior según la bandera $anterior
    if ($anterior) {
        $añoActual--;
    }

    // Consultar la base de datos para obtener las fechas de cumpleaños anteriores o posteriores
    $orden = $anterior ? "DESC" : "ASC";
    $sql = "SELECT Fecha_nacimiento
            FROM datospersonales
            WHERE DATE_FORMAT(Fecha_nacimiento, '%m-%d') $orden '$mesDiaReferencia'
            AND YEAR(Fecha_nacimiento) <= $añoActual
            LIMIT 1";

    $resultado = $cn->query($sql);

    if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        $fechaCumple = $row["Fecha_nacimiento"];
    } else {
        $fechaCumple = ""; // No se encontró ninguna fecha
    }

    return $fechaCumple;
}
