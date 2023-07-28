<?php 
include("cabecera_pie.php");

// Función para convertir los caracteres especiales a entidades HTML
function convertirCaracteresEspeciales($string) {
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html>
<meta charset="utf-8"><head>
    <title>Importar y Exportar Datos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F2F2F2;
            margin: 0;
            padding: 0;
        }
        
        header {
            background-color: #1E88E5;
            padding: 20px;
            text-align: center;
            color: #FFF;
            margin-bottom: 10px;
        }
        
        h1 {
            margin: 0;
            font-size: 24px;
        }
        
        form {
            margin: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 10px;
        }
        
        input[type="file"], input[type="submit"] {
            padding: 10px 15px;
            background-color: #1E88E5;
            color: #FFF;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        input[type="file"]:hover, input[type="submit"]:hover {
            background-color: #1565C0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Importar y Exportar Datos</h1>
    </header>
    
    <form action="importarproceso.php" method="post" enctype="multipart/form-data">
        <label for="archivo">Seleccionar archivo para importar:</label>
        <input type="file" name="archivo" id="archivo">
        <input type="submit" name="importar" value="Importar Datos">
    </form>

    <form action="exportarproceso.php" method="post">
        <input type="hidden" name="exportar" value="1">
        <input type="submit" value="Exportar Datos">
    </form>

    <script>
        // Obtener el parámetro "mensaje" de la URL
        const urlParams = new URLSearchParams(window.location.search);
        const mensaje = urlParams.get('mensaje');

        // Mostrar el mensaje si existe
        if (mensaje) {
            alert(mensaje);
        }
    </script>

    <!-- Agregar una barra de progreso -->
<div id="progress-bar-container" style="display:none;">
    <div id="progress-bar" style="width: 0%;">0%</div>
</div>


<!-- Agregar el código JavaScript para mostrar la barra de progreso cuando se esté importando -->
<script>
    // Mostrar la barra de progreso si el progreso está en curso
    if (window.location.search.includes("progress=1")) {
        document.getElementById("progress-bar-container").style.display = "block";
    }
</script>


</body>
</html>