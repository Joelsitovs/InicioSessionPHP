<?php
$host = ''; // Host de la base de datos
$dbname = ''; // Nombre de la base de datos
$user = ''; // Usuario de la base de datos
$pass = ''; // Contraseña de la base de datos

$conexion = new mysqli($host, $user, $pass, $dbname);
if ($conexion->connect_error) {
    die("La conexión falló: " . $conexion->connect_error);
}
?>