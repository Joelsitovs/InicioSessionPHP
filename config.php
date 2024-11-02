<?php
require './vendor/autoload.php';

// Asegúrate de que la ruta a la carpeta .env sea correcta
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ ); 
$dotenv->load();

$clientID = $_ENV['CLIENT_ID'];
$clientSecret = $_ENV['CLIENT_SECRET'];
$redirectURI = $_ENV['REDIRECT_URI'];


// Prueba a imprimir las variables para asegurarte de que se cargan correctamente
echo "Client ID: $clientID<br>";
echo "Client Secret: $clientSecret<br>";
echo "Redirect URI: $redirectURI<br>";

/*$clientID ="743..";
$clientSecret = "GOCS..";
$redirectURI = 	"https://";
*/


?>


