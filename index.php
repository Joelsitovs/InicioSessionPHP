<?php
session_start();

// Verificamos si el usuario ha iniciado sesión
if (isset($_SESSION['user'])) { // Cambia 'user' por la clave que uses para almacenar el nombre de usuario
    echo "Hola, " . htmlspecialchars($_SESSION['user']) . ", acabas de iniciar sesión.";
} else {
    echo "No has iniciado sesión. Por favor, inicia sesión para continuar.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php if (isset($_SESSION['user'])): ?>
    <a href="./handlers/logout.php">Cerrar sesión</a>
<?php else: ?>
    <a href="./login.php">Iniciar sesión</a>
<?php endif; ?>
</body>
</html>
