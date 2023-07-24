<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Foto</title>
</head>
<body>
    <h1>Agregar Foto</h1>
    <form method="post" enctype="multipart/form-data" action="agregarfoto.php">
        <!-- Campo oculto para enviar el ID de la persona -->
        <input type="hidden" name="id_persona" value="<?php echo $_GET['id_persona']; ?>">

        <label for="foto">Seleccione una foto:</label>
        <input type="file" id="foto" name="foto" accept="image/*" required>

        <input type="submit" value="Agregar Foto">
    </form>
</body>
</html>
