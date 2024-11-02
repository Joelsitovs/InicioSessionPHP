<?php
include '../sql/conexionsql_user.php'; // Incluir el archivo de conexión a la base de datos

// Función para validar entradas
function validar_usuario($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Función para verificar campos requeridos
function campos_requeridos($usuario, $contraseña) {
    return !empty($usuario) && !empty($contraseña);
}

// Función para redirigir con mensaje de error
function redirigir_con_error($mensaje) {
    header("Location: ../login.php?action=login&error=" . urlencode($mensaje));
    exit();
}

// Función para comprobar si el usuario existe en la base de datos y verificar la contraseña
function comprobar_usuario($conexion, $usuario, $correo, $contraseña) {
    $sql = "SELECT * FROM users WHERE (usuario = ? OR correo = ?) ";
    $stmt = $conexion->prepare($sql);

    // Verificar si la preparación fue exitosa
    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . htmlspecialchars($conexion->error));
    }

    // Vincular los parámetros
    $stmt->bind_param("ss", $usuario, $correo); // Vincula el nombre de usuario o correo

    // Ejecutar la consulta
    if (!$stmt->execute()) {
        die('Error en la ejecución de la consulta: ' . htmlspecialchars($stmt->error));
    }

    // Obtener el resultado
    $resultado = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Verificar la contraseña
    if ($resultado && password_verify($contraseña, $resultado['contraseña'])) {
        return $resultado; // Retorna los datos del usuario si es correcto
    }
    
    return false; 
}

function iniciar_sesion($usuario_datos) {
    session_start();
    $_SESSION['user'] = $usuario_datos['usuario']; // Nombre del usuario
    header("Location: ../index.php"); // Redirigir a la página principal después del login
    exit();
}

// Función para el formulario de login
function formulario_login($conexion) {
    if (isset($_POST['username'], $_POST['passwd'])) {
        // Validar y asignar valores de entrada
        $usuario = validar_usuario($_POST['username']);
        $contraseña = validar_usuario($_POST['passwd']);
        $correo = $usuario; // Suponiendo que el nombre de usuario puede ser un correo

        // Verificar que los campos no estén vacíos
        if (!campos_requeridos($usuario, $contraseña)) {
            redirigir_con_error('Todos los campos son requeridos');
        }
  // Comprobar si el usuario existe y la contraseña es válida
  $resultado = comprobar_usuario($conexion, $usuario, $correo, $contraseña);
  if (!$resultado) {
      redirigir_con_error('Usuario o contraseña incorrectos');
  }

  // Verificar si el usuario está confirmado
  if ($resultado['confirmado'] == 0) {
      redirigir_con_error('Usuario no confirmado, por favor verifica tu correo');
  }
        // Iniciar sesión
        iniciar_sesion($resultado);
        
    } else {
        redirigir_con_error('Los datos de inicio de sesión son requeridos.');
    }
}

// Llamada a la función del formulario de login
formulario_login($conexion);
?>