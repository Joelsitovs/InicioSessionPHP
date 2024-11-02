<?php
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$clientID = getenv('CLIENT_ID');
$clientSecret = getenv('CLIENT_SECRET');
$redirectURI = getenv('REDIRECT_URI');

// Ahora puedes usar estas variables en tu código
?>
