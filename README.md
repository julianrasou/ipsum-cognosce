# ipsum-cognosce

Esta app web consiste en una plataforma de productividad con diferentes herramientas:
- Reloj pomodoro
- Lista de tareas (Requiere inicio de sesión)
- Lista de notas (Requiere inicio de sesión)

## Requisitos

- [XAMPP](https://www.apachefriends.org/index.html) instalado (PHP, MySQL, Apache)
- Navegador web moderno
- Editor de código (opcional, pero recomendado)

## Cómo lanzar la aplicación

### 1. Clonar o copiar el proyecto

Coloca los archivos del proyecto en la carpeta `htdocs` de tu instalación de XAMPP.

### 2. Iniciar Apache y MySQL

Abre el panel de control de XAMPP y haz clic en "Start" en los módulos **Apache** y **MySQL**.

### 3. Crear la base de datos

1. Abre tu navegador y ve a [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
2. Haz clic en SQL y copia el contenido del archivo database.sql en app/models/database.sql
3. Ejecuta

### 4. Abrir la app en el navegador

Abre tu navegador y ve a [http://localhost/ipsum-cognosce](http://localhost/ipsum-cognosce).