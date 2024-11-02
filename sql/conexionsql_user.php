<?php
$host = '193.203.168.81'; // Host de la base de datos
$dbname = 'u328800997_Dmondejarm'; // Nombre de la base de datos
$user = 'u328800997_rootDmondejarm'; // Usuario de la base de datos
$pass = 'F5^Z#rU1u&h'; // Contraseña de la base de datos

$conexion = new mysqli($host, $user, $pass, $dbname);
if ($conexion->connect_error) {
    die("La conexión falló: " . $conexion->connect_error);
}
?>