# InicioSesionPhp
Para probar esto puedes ingresar a la siguiente url https://iniciarsesion.caowthing.es/login.php?action=login


Este proyecto proporciona un sistema de autenticación de usuarios que incluye funcionalidades para el inicio de sesión, registro y verificación segura de credenciales.

## Características Principales

- **Registro de Usuarios**: 
  - Los nuevos usuarios pueden registrarse proporcionando un nombre de usuario, correo electrónico y contraseña.
  - Se realiza la validación de datos y el almacenamiento seguro en una base de datos.

- **Inicio de Sesión Seguro**: 
  - Los usuarios pueden autenticarse mediante la validación de contraseñas cifradas.

- **Verificación de Usuario**: 
  - Solo los usuarios confirmados pueden acceder al sistema, con mensajes de error claros para datos incorrectos o cuentas no verificadas.

- **Manejo de Sesiones**: 
  - Las sesiones de usuario se gestionan para mantener la conexión de manera segura durante la visita.

- **Notificaciones de Estado**: 
  - Incluye alertas de éxito o error al iniciar sesión, registrarse, o en caso de problemas de autenticación.

## Tecnologías Utilizadas

- **Backend**: PHP para la lógica del servidor y manejo de sesiones.
- **Frontend**: HTML y CSS para la interfaz de usuario.
- **Base de Datos**: MySQL para el almacenamiento de datos.

## Instalación

Para ejecutar este proyecto, sigue estos pasos:

### 1. Requisitos Previos

Asegúrate de tener instalado lo siguiente en tu sistema:

- **PHP**: Versión 7.4 o superior.
- **Composer**: Un gestor de dependencias para PHP.
- **MySQL**: Para la base de datos.
- Un servidor web como **Apache** o **Nginx**.

### 2. Clona el Repositorio

Clona este repositorio en tu entorno local utilizando Git. Abre una terminal y ejecuta:

```bash
git clone https://github.com/Joelsitovs/InicioSesionPhp.git

cd InicioSesionPhp

``` 
### 3. Instala Dependencias

Asegúrate de que Composer esté instalado y luego ejecuta los siguientes comandos para instalar las bibliotecas necesarias:

Google API Client:

```bash

composer require google/apiclient:"^2.0"

``` 
PHPMailer: Para el envío de correos electrónicos.

```bash
composer require phpmailer/phpmailer


``` 
vlucas/phpdotenv: Para manejar variables de entorno.

```bash
composer require vlucas/phpdotenv
```
### 4. Configura la Base de datos 

Para que tu sistema de autenticación funcione correctamente, es necesario configurar una base de datos MySQL. Sigue los pasos a continuación para crear y configurar la base de datos:

### 4.1 Crea la Base de Datos

Abre tu cliente de MySQL (como phpMyAdmin o la línea de comandos de MySQL) y ejecuta el siguiente comando para crear una nueva base de datos:

```bash 
CREATE DATABASE nombre_de_tu_base_de_datos;
``` 
Después de crear la base de datos, selecciona la base de datos que acabas de crear y ejecuta el siguiente comando para crear la tabla de usuarios y usuarios_google:
```bash
USE nombre_de_tu_base_de_datos;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) DEFAULT NULL,
  `correo` varchar(250) NOT NULL,
  `contraseña` varchar(500) DEFAULT NULL,
  `token` varchar(250) NOT NULL,
  `confirmado` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE users_google(
    id int not null AUTO_INCREMENT,
    usuario varchar(50),
    correo varchar(500),
    Primary key (id)
)

```
Puedes añadir mas campos segun lo que tu necesites

### 4.2 Configura el Archivo de Conexión a la Base de Datos

En el archivo se encuentra en sql/config.php, asegúrate de configurar las credenciales de conexión a la base de datos

Si haces alguna modificación de código, no olvides actualizar los archivos correspondientes para reflejar esos cambios. Esto es importante para garantizar que tu aplicación funcione correctamente.


### 5 Configuracion de mail

Para que el sistema de autenticación funcione correctamente, es necesario configurar el envío de correos electrónicos. Esto es fundamental para las funcionalidades de verificación de cuenta 

Configura el Archivo de Envío de Correo: Abre el archivo procesarRegistro.php y modifica los datos necesarios en la función enviar_correo_confirmacion.