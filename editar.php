<?php
include("conexion.php");

//include("mas_informacion.php");

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

    <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Datos Personales</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
        }
        
        form {
            max-width: 500px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        fieldset {
            border: none;
            padding: 0;
            margin-bottom: 20px;
        }
        
        legend {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }
        
        select {
            height: 40px;
        }
        
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<form action="actualizar.php" method="post">
    <fieldset>
        <legend>Datos Personales</legend>
        <label for="txtpaterno">Apellido Paterno:</label>
        <input type="text" name="txtpaterno" id="txtpaterno" placeholder="Ap. Paterno" autocomplete="off" value="<?php echo $datosPersonales["Apellido_paterno"]; ?>">
        
        <label for="txtmaterno">Apellido Materno:</label>
        <input type="text" name="txtmaterno" id="txtmaterno" placeholder="Ap. Materno" value="<?php echo $datosPersonales["Apellido_materno"]; ?>">
        
        <label for="txtnombre">Nombres:</label>
        <input type="text" name="txtnombre" id="txtnombre" placeholder="Nombres" value="<?php echo $datosPersonales["Nombres"]; ?>">
        
        <label for="txtdni">DNI:</label>
        <input type="text" name="txtdni" id="txtdni" placeholder="DNI" value="<?php echo $datosPersonales["Dni"]; ?>">
        
        <label for="sexo">Sexo:</label>
        <select id="sexo" name="sexo">
            <option value="M" <?php if ($datosPersonales["SEXO"] == "M") echo "selected"; ?>>Masculino</option>
            <option value="F" <?php if ($datosPersonales["SEXO"] == "F") echo "selected"; ?>>Femenino</option>
        </select>
        
        <label for="correo">Correo Electrónico:</label>
        <input type="email" name="correo" id="correo" placeholder="Correo Electrónico" value="<?php echo $datosPersonales["correo"]; ?>">
        
        <label for="celular">Celular:</label>
        <input type="tel" name="celular" id="celular" placeholder="Celular" value="<?php echo $datosPersonales["Celular"]; ?>">
        
        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" placeholder="Fecha de Nacimiento" value="<?php echo $datosPersonales["Fecha_nacimiento"]; ?>">
    </fieldset>

    <fieldset>
        <legend>Datos Académicos</legend>
        <label for="facultad">Facultad:</label>
        <select id="facultad" name="facultad">
            <?php
            $sql = "SELECT * FROM facultad";
            $result = mysqli_query($cn, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                $selected = ($row["id_facultad"] == $datosPersonales["id_Facu"]) ? "selected" : "";
                echo '<option value="' . $row["id_facultad"] . '" ' . $selected . '>' . $row["nomfacultad"] . '</option>';
            }
            ?>
        </select>

        <label for="cargo">Cargo:</label>
        <select id="cargo" name="cargo">
            <?php
            $sql = "SELECT * FROM cargo";
            $result = mysqli_query($cn, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                $selected = ($row["id_cargo"] == $datosPersonales["id_car"]) ? "selected" : "";
                echo '<option value="' . $row["id_cargo"] . '" ' . $selected . '>' . $row["nomcargo"] . '</option>';
            }
            ?>
        </select>

        <label for="dedicacion">Dedicación:</label>
        <select id="dedicacion" name="dedicacion">
            <?php
            $sql = "SELECT * FROM dedicacion";
            $result = mysqli_query($cn, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                $selected = ($row["id_dedicacion"] == $datosPersonales["id_Dedi"]) ? "selected" : "";
                echo '<option value="' . $row["id_dedicacion"] . '" ' . $selected . '>' . $row["nomdedicacion"] . '</option>';
            }
            ?>
        </select>

        <label for="maestria">Maestría:</label>
        <select id="maestria" name="maestria">
            <?php
            $sql = "SELECT * FROM maestria";
            $result = mysqli_query($cn, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                $selected = ($row["id_maestria"] == $datosPersonales["id_maes"]) ? "selected" : "";
                echo '<option value="' . $row["id_maestria"] . '" ' . $selected . '>' . $row["nommaestria"] . '</option>';
            }
            ?>
        </select>

        <label for="grado">Grado:</label>
        <select id="grado" name="grado">
            <?php
            $sql = "SELECT * FROM grado";
            $result = mysqli_query($cn, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                $selected = ($row["id_grado"] == $datosPersonales["id_gra"]) ? "selected" : "";
                echo '<option value="' . $row["id_grado"] . '" ' . $selected . '>' . $row["nomgrado"] . '</option>';
            }
            ?>
        </select>

        <label for="categoria">Categoría:</label>
        <select id="categoria" name="categoria">
            <?php
            $sql = "SELECT * FROM categoria";
            $result = mysqli_query($cn, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                $selected = ($row["id_categoria"] == $datosPersonales["id_cate"]) ? "selected" : "";
                echo '<option value="' . $row["id_categoria"] . '" ' . $selected . '>' . $row["nomcategoria"] . '</option>';
            }
            ?>
        </select>
        
        <input type="hidden" name="id_datos_personales" value="<?php echo $id; ?>">
    </fieldset>

    <input type="submit" value="Actualizar">
</form>

</body>
</html>

<?php
} else {
    echo "No se encontraron datos.";
}

$resultado->close();
$cn->close();
}
?>
