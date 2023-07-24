<?php
// opciones.php
include("conexion.php");

// Consulta para obtener el último ID agregado en la tabla "datospersonales"
$sql = "SELECT id_datos_personales, Apellido_paterno, Apellido_materno, Nombres FROM datospersonales ORDER BY id_datos_personales DESC LIMIT 1";
$resultado = mysqli_query($cn, $sql);

// Verificar si la consulta se realizó correctamente
if ($resultado) {
    $fila = mysqli_fetch_assoc($resultado);
    $ultimo_id = $fila['id_datos_personales'];
    $nombre_completo = $fila['Apellido_paterno'] . ' ' . $fila['Apellido_materno'] . ' ' . $fila['Nombres'];
} else {
    // Error al realizar la consulta
    echo "Error al obtener el último ID agregado.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
   
    if (isset($_POST['btn_si'])) {
        // User clicked "Guardar sin foto" button
        // Process the data accordingly
    } elseif (isset($_POST['btn_agregar_foto'])) {
        
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opciones</title>
</head>
<body>
    <div class="container">
        <h1>Bienvenido/a</h1>
        <h3>Por favor, agregue una foto de su rostro de forma presentable:</h3>

        <!-- Display the person's name obtained from the database -->
        <p>Nombre: <?php echo $nombre_completo; ?></p>

        <form method="post" enctype="multipart/form-data" action="procesoagregarfoto.php">
            <!-- Agregar el ID de la persona como campo oculto -->
            <input type="hidden" name="id_persona" value="<?php echo $ultimo_id; ?>">
            <input type="file" name="foto" accept="image/*" id="fotoInput"> <!-- Campo para cargar la foto -->
            <div style="display: flex; align-items: center;">
                <input type="submit" name="btn_agregar_foto" value="Agregar foto">
                <div style="margin-left: 10px;">
                    <input type="submit" name="btn_si" value="Guardar sin foto" style="background-color: #d62828;"> <!-- Changed button color -->
                </div>
            </div>
        </form>

        <!-- Elemento para mostrar la vista previa de la foto -->
        <div id="vistaPrevia">
            <img id="imagenPrevia" src="#" alt="Vista previa de la foto" style="display: none; max-width: 200px;">
        </div>
    </div>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f2f7; /* Celeste claro */
            color: #000000; /* Negro */
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff; /* Blanco */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #0077b6; /* Celeste más oscuro */
        }

        h3 {
            color: #173f5f; /* Celeste oscuro */
        }

        input[type="submit"] {
            background-color: #0077b6; /* Celeste más oscuro */
            color: #ffffff; /* Blanco */
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            margin-right: 10px;
        }

        input[type="submit"]:hover {
            background-color: #023e8a; /* Celeste aún más oscuro en el hover */
        }
    </style>

    <script>
        // Función para mostrar la vista previa de la foto seleccionada
        function mostrarVistaPrevia(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    document.getElementById('imagenPrevia').style.display = 'block';
                    document.getElementById('imagenPrevia').src = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        // Asignar el evento change al campo de carga de la foto
        document.getElementById('fotoInput').addEventListener('change', function () {
            mostrarVistaPrevia(this);
        });
    </script>
</body>
</html>
