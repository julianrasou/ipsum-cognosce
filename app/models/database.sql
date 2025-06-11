-- Crea la base de datos
CREATE DATABASE ipsum_cognosce;

-- Crea un usuario que será el que use la base de datos del proyecto
-- No se usa el usuario root por motivos de seguridad
CREATE USER 'ipsum_user'@'localhost' IDENTIFIED BY '1234';

-- Le da todos los privilegios sobre la base de datos
GRANT ALL PRIVILEGES ON ipsum_cognosce.* TO 'ipsum_user'@'localhost';

-- Aplica los cambios
FLUSH PRIVILEGES;

-- Usa la base de datos
USE ipsum_cognosce;

-- Crea la tabla usuarios
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Crea la tabla categorías de tareas
CREATE TABLE task_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- crea la tabla tareas
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    status BOOLEAN NOT NULL DEFAULT FALSE,
    category_id INT,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (category_id) REFERENCES task_categories(id) ON DELETE CASCADE ON UPDATE CASCADE
);
