<!DOCTYPE html>
<html>
<head>
    <title>Formulario para borrar datos</title>
</head>
<body>
    <h2>Borrar todos los datos</h2>
    <form action="borrar_datos.php" method="post">
        <label>
            <input type="checkbox" name="confirmar" required>
            Confirmo que deseo borrar todos los datos de la base de datos.
        </label>
        <br>
        <input type="submit" value="Borrar datos" name="submit">
    </form>
</body>
</html>
