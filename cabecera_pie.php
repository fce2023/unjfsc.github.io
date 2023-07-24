<!DOCTYPE html>
<html>
<head>
    <title>Facultad de Ciencias Empresariales</title>
   
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
        
        .enlaces_superiores {
            display: block;
            padding: 10px 15px;
            background-color: #1E88E5;
            color: #FFF;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s ease;
            border-radius: 5px;
        }
        
        .enlaces_superiores:hover {
            background-color: #1565C0;
        }
        
        table {
            width: 100%;
            background-color: #FFF;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        td {
            text-align: center;
            padding: 20px;
        }
        
        /* Estilos para dispositivos móviles */
        @media (max-width: 768px) {
            h1 {
                font-size: 20px;
            }
            
            .enlaces_superiores {
                padding: 5px 10px;
                font-size: 16px;
            }
            
            td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>FACULTAD DE CIENCIAS EMPRESARIALES</h1>
    </header>
    
    <center>
        <table>
            <tr align="center">
                <td style="width:25%;">
                    <a href="index.php" target="_parent" class="enlaces_superiores">
                        Registro
                    </a>
                </td>
                <td style="width:25%;">
                    <a href="exportar_importar.php" target="_parent" class="enlaces_superiores">
                        Exportar/Importar
                    </a>
                </td>
                <td style="width:25%">
                    <a href="lista.php" target="_parent" class="enlaces_superiores">
                        Lista General
                    </a>
                </td>
                <td style="width:25%">
                    <a href="listaqr.php" target="_parent" class="enlaces_superiores">
                        QR Informacion
                    </a>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
