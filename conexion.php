<?php
$cn = mysqli_init();
mysqli_ssl_set($cn, NULL, NULL, "DigiCertGlobalRootCA.crt.pem", NULL, NULL);
mysqli_real_connect($cn, "fce.mysql.database.azure.com", "fce", "*royrj97*", "fce", 3306, MYSQLI_CLIENT_SSL);
?>