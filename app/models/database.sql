-- Create the database
CREATE DATABASE ipsum_cognosce;

-- Create a new user (replace 'username' and 'password')
CREATE USER 'ipsum_user'@'localhost' IDENTIFIED BY '1234';

-- Grant all privileges on the database to the user
GRANT ALL PRIVILEGES ON ipsum_cognosce.* TO 'ipsum_user'@'localhost';

-- Apply the changes
FLUSH PRIVILEGES;

USE ipsum_cognosce;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE task_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    status BOOLEAN NOT NULL DEFAULT FALSE, -- FALSE = pending, TRUE = completed
    category_id INT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (category_id) REFERENCES task_categories(id) ON DELETE CASCADE ON UPDATE CASCADE
);
