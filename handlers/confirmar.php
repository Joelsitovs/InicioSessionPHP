<?php

include '../sql/conexionsql_user.php';

// Funcion para redirigir con mensaje de error o exito
 function redirigir($mensaje, $success = true){
    $tipo = $success ? 'success' : 'error';
    header("Location: ../login.php?$tipo=".urlencode($mensaje));
 }

 // Verificar si se ha enviado un token 
 if(isset($_GET['token'])){
    $token = $_GET['token'];
    // Preparar la consulta SQL
    $sql = "SELECT * FROM users WHERE token = ?";
    $stmt = $conexion->prepare($sql);
    // Verificar si la preparacion fue exitosa
    if($stmt === false) die('Error en la preparacion de la consulta: ' . htmlspecialchars($conexion->error));
    // Vincular los parametros
    $stmt->bind_param("s",$token);
    // Ejecutar la consulta
    if(!$stmt->execute()) die('Error en la ejecucion de la consulta: ' . htmlspecialchars($stmt->error));
    // Retornar el resultado si existe
    $resultado = $stmt->get_result()->fetch_assoc(); // Retorna el usuario si existe
    $stmt->close(); // Cerrar la consulta
    // Verificar si el token es valido
    if($resultado){
        $sql = "UPDATE users SET confirmado = 1 WHERE token = ?";
        $stmt = $conexion->prepare($sql);
        // Verificar si la preparacion fue exitosa
        if($stmt === false) die('Error en la preparacion de la consulta: ' . htmlspecialchars($conexion->error));
        // Vincular los parametros
        $stmt->bind_param("s",$token);
        // Ejecutar la consulta 
        if(!$stmt->execute()) die('Error en la ejecucion de la consulta: ' . htmlspecialchars($stmt->error));
        $stmt->close(); // Cerrar la consulta
        redirigir('Usuario confirmado con exito');
    }else{
        redirigir('Token invalido o no encontrado', false);

    }
}else{
        redirigir('No se ha proporcionado un token', false);
    }


?>