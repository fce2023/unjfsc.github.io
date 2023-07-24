<?php
include("conexion.php");
include("cabecera_pie.php");
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Selects desde la Base de Datos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 20px;
        }

        fieldset {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #dddddd;
        }

        legend {
            font-weight: bold;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input.input-small,
        select.input-small {
            width: 45%;
            margin-right: 5%;
        }

        input, select {
            width: 100%;
            padding: 5px;
            border: 1px solid #cccccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #ffffff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .form-row .form-column {
            width: calc(50% - 5px);
        }

        .form-row-2col .form-column {
            width: calc(50% - 5px);
            margin-bottom: 10px;
        }

        .form-row-2col .form-column:first-child {
            margin-right: 10px;
        }
        
        .form-row .form-column {
    width: calc(45% - 5%);
    margin-right: 10%;
}
.form-row-2col .form-column {
    width: calc(30-5%);
    margin-bottom: 10px;
}



    </style>
</head>
<body>

<form action="guardar.php" method="post">
    <fieldset>
        <legend>Datos Personales</legend>
        <div class="form-row">
            <div class="form-column">
                <label for="txtpaterno">Apellido Paterno:</label>
                <input type="text" name="txtpaterno" id="txtpaterno" placeholder="Ap. Paterno" autocomplete="off">
            </div>
            <div class="form-column">
                <label for="txtmaterno">Apellido Materno:</label>
                <input type="text" name="txtmaterno" id="txtmaterno" placeholder="Ap. Materno">
            </div>
        </div>

        <div class="form-row">
            <div class="form-column">
                <label for="txtnombre">Nombres:</label>
                <input type="text" name="txtnombre" id="txtnombre" placeholder="Nombres">
            </div>
            <div class="form-column">
                <label for="txtdni">DNI / Sexo:</label>
                <input type="text" name="txtdni" id="txtdni" placeholder="DNI">
                <select id="sexo" name="sexo">
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-column">
                <label for="correo">Correo Electrónico:</label>
                <input type="email" name="correo" id="correo" placeholder="Correo Electrónico">
            </div>
            <div class="form-column">
                <label for="celular">Celular:</label>
                <input type="tel" name="celular" id="celular" placeholder="Celular">
            </div>
        </div>

        <div class="form-row">
            <div class="form-column">
                <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" placeholder="Fecha de Nacimiento">
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend>Datos Académicos</legend>
        <div class="form-row form-row-2col">
            <div class="form-column">
                <label for="facultad">Facultad:</label>
                <select class="input-small" id="facultad" name="facultad">
                    <?php
                    $sql = "SELECT * FROM facultad";
                    $result = mysqli_query($cn, $sql);

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row["id_facultad"] . '">' . $row["nomfacultad"] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-column">
                <label for="dedicacion">Dedicación:</label>
                <select class="input-small" id="dedicacion" name="dedicacion">
                    <?php
                    $sql = "SELECT * FROM dedicacion";
                    $result = mysqli_query($cn, $sql);

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row["id_dedicacion"] . '">' . $row["nomdedicacion"] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-column">
                <label for="cargo">Cargo:</label>
                <select class="input-small" id="cargo" name="cargo">
                    <?php
                    $sql = "SELECT * FROM cargo";
                    $result = mysqli_query($cn, $sql);

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row["id_cargo"] . '">' . $row["nomcargo"] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-column">
                <label for="maestria">Maestría:</label>
                <select id="maestria" name="maestria">
                    <?php
                    $sql = "SELECT * FROM maestria";
                    $result = mysqli_query($cn, $sql);

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row["id_maestria"] . '">' . $row["nommaestria"] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-column">
                <label for="grado">Grado:</label>
                <select class="input-small" id="grado" name="grado">
                    <?php
                    $sql = "SELECT * FROM grado";
                    $result = mysqli_query($cn, $sql);

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row["id_grado"] . '">' . $row["nomgrado"] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-column">
                <label for="categoria">Categoría:</label>
                <select class="input-small" id="categoria" name="categoria">
                    <?php
                    $sql = "SELECT * FROM categoria";
                    $result = mysqli_query($cn, $sql);

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row["id_categoria"] . '">' . $row["nomcategoria"] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </fieldset>

    <input type="submit" value="Guardar">
</form>

</body>
</html>
