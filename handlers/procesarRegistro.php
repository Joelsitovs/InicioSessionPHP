<?php
// Importar las clases necesarias de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Incluir el archivo autoload.php de la carpeta vendor
require '../vendor/autoload.php';

// Incluir la conexión a la base de datos
include '../sql/conexionsql_user.php';
// Función para validar entradas
function validar_usuario($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
// Función para validar la longitud de la contraseña
function validar_longitud_contraseña($contraseña) {
    return strlen($contraseña) >= 8; // Verifica que la longitud sea al menos 8
}

// Funcion para Campos requeridos
function campos_requeridos($usuario,$correo,$contraseña,$contraseña_confirm){
    return !empty($usuario) && !empty($correo)  && !empty($contraseña) && !empty($contraseña_confirm);
}

// Funcion para comparar contraseñas
function comparar_contraseñas($contraseña,$contraseña_confirm){
    return ($contraseña === $contraseña_confirm);
}

// Funcion para redirigir con mensaje de error
function redirigir_con_error($mensaje){
    header("Location: ../login.php?action=register&error=".urlencode($mensaje));
    exit();
}

// Funcion para redirigir con mensaje de exito
function redirigir_con_exito($mensaje){
    header("Location: ../login.php?success=".urlencode($mensaje));
    exit();
}

// Funcion para verificar si el usuario ya existe en la base de datos
function usuario_existente($conexion,$usuario,$correo){
    // Preparar la consulta SQL
    $sql = "SELECT * FROM users WHERE usuario = ? OR correo = ?";
    $stmt = $conexion->prepare($sql);
    // Verificar si la preparación fue exitosa
    if($stmt === false) die('Eror enla preparacion de la consulta: ' . htmlspecialchars($conexion->error));
    // Vincular los parametros
    $stmt->bind_param("ss",$usuario,$correo);
    // Ejecutar la consulta
    if(!$stmt->execute()) die('Error en la ejecucion de la consulta: ' . htmlspecialchars($stmt->error));
    // Retornar el resultado si existe
    $resultado = $stmt->get_result()->fetch_assoc(); // Retorna el usuario si existe
    $stmt->close(); // Cerrar la consulta
    return $resultado;
}
// Funcion para encriptar la contraseña
function encriptar_contraseña($contraseña){
    return password_hash($contraseña,PASSWORD_DEFAULT);
}

// Funcion para registrar un nuevo usuario
function registrar_usuario($conexion,$usuario,$correo,$contraseña){
    // Preparar la consulta SQL
    $sql = "INSERT INTO users (usuario,correo,contraseña,token,confirmado) VALUES (?,?,?,?,0)";
    $stmt = $conexion->prepare($sql);
    // Verificar si la preparacion fue exitosa
    if ($stmt === false) die('Error en la preparacion de la consulta: ' . htmlspecialchars($conexion->error));
    // Encriptar la contraseña
    $hash = encriptar_contraseña($contraseña);
    $token = bin2hex(random_bytes(15));
        // Vincular los parámetros 
        $stmt->bind_param("ssss", $usuario, $correo, $hash, $token);
    // Ejecutar la consulta
    if(!$stmt->execute()) die('Error en la ejecucion de la consulta: ' . htmlspecialchars($stmt->error));
    // Cerrar la consulta
    $stmt->close();
    // Enviar correo de confirmacion
    enviar_correo_confirmacion($correo,$token);
}

 // Función para enviar correo de confirmación
function enviar_correo_confirmacion($correo, $token) {
    $mail = new PHPMailer(true); // Instanciar PHPMailer
    try {
        // Configuración del servidor SMTP
        $mail->isSMTP(); 
        $mail->Host = 'smtp.hostinger.com';  // Cambia por tu servidor SMTP
        $mail->SMTPAuth = true; 
        $mail->Username = 'prueba@pruebas.prueba.es'; // Cambia por tu correo
        $mail->Password = 'passwwd'; // Cambia por tu contraseña
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
        $mail->Port = 465; 

        // Remitente y destinatario
        $mail->setFrom('prueba@pruebas.prueba.es', 'Prueba'); // Cambia por tu correo // Cambia por tu nombre
        $mail->addAddress($correo); 

        // Contenido del correo
        $mail->isHTML(true); 
        $mail->Subject = 'Confirma tu correo';
        $mail->Body = 'Por favor confirma tu correo haciendo clic en el siguiente enlace: ' . 
               'https://' . $_SERVER['HTTP_HOST'] . '/handlers/confirmar.php?token=' . $token;


        // Enviar el correo
        $mail->send();
        echo 'Correo de confirmación enviado';
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }
    //https://pruebas.caowthing.es/
}
// Función para recibir los datos del formulario
function recibirdatos($conexion) {
    // Verificar si se enviaron los datos del formulario
    if (isset($_POST['username'], $_POST['email'], $_POST['passwd'], $_POST['passwd_confirm'])) {
        // Limpiar y validar los datos
        $usuario = validar_usuario($_POST['username']);
        $correo = validar_usuario($_POST['email']);
        $contraseña = validar_usuario($_POST['passwd']);
        $contraseña_confirm = validar_usuario($_POST['passwd_confirm']);

        // Verificar si los campos no están vacíos
        if (!campos_requeridos($usuario, $correo, $contraseña, $contraseña_confirm)) {
            redirigir_con_error('Por favor complete todos los campos');
        }

        // Verificar si las contraseñas coinciden
        if (!comparar_contraseñas($contraseña, $contraseña_confirm)) {
            redirigir_con_error('Las contraseñas no coinciden');
        }

        // Verificar si el usuario ya existe
        if (usuario_existente($conexion, $usuario, $correo)) {
            redirigir_con_error('El usuario o correo ya existe');
        }
        if (!validar_longitud_contraseña($contraseña)) {
            redirigir_con_error('La contraseña debe tener al menos 8 caracteres');
        
        }

        // Registrar el nuevo usuario
        registrar_usuario($conexion, $usuario, $correo, $contraseña);
        redirigir_con_exito('Usuario registrado con éxito. Por favor confirma tu correo.');
    } else {
        redirigir_con_error('Por favor complete todos los campos');
    }
}

// Recibir los datos del formulario
recibirdatos($conexion);
?>