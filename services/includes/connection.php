<?php
    $servername = "usedmsqllicensing001.mysql.database.azure.com";
    $username = "licensing_service";
    $password = "EU3zhgnSzgdR4kPF";
    $dbname = "biggerbrains_licensing";

    $conn = mysqli_init();
    mysqli_ssl_set($conn,NULL,NULL, "//var/www/html/services/includes/BaltimoreCyberTrustRoot.crt.pem", NULL, NULL);
    mysqli_real_connect($conn, 'usedmsqllicensing001.mysql.database.azure.com', 'licensing_service', 'EU3zhgnSzgdR4kPF', 'biggerbrains_licensing', 3306, MYSQLI_CLIENT_SSL);
    if (mysqli_connect_errno($conn)) {
        die('Failed to connect to MySQL: '.mysqli_connect_error());
    }

?>