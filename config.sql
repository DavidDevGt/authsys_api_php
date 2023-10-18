CREATE DATABASE auth_template;

USE auth_template;

CREATE TABLE user_auth (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_verified BOOLEAN DEFAULT FALSE
);

CREATE TABLE user_profile (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_auth_id INT,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    fecha_nacimiento DATE,
    address VARCHAR(255),
    FOREIGN KEY (user_auth_id) REFERENCES user_auth(id)
);

CREATE TABLE verification_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_auth_id INT,
    token CHAR(5),
    created_at DATETIME,
    FOREIGN KEY (user_auth_id) REFERENCES user_auth(id)
);

CREATE TABLE user_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_auth_id INT,
    session_id CHAR(32),
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (user_auth_id) REFERENCES user_auth(id)
);

CREATE TABLE user_roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    description VARCHAR(255)
);

CREATE TABLE user_role_assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_auth_id INT,
    role_id INT,
    FOREIGN KEY (user_auth_id) REFERENCES user_auth(id),
    FOREIGN KEY (role_id) REFERENCES user_roles(id)
);

CREATE TABLE permissions (
id INT AUTO_INCREMENT PRIMARY KEY,
role_id INT,
permission VARCHAR(255),
FOREIGN KEY (role_id) REFERENCES user_roles(id)
);

-- Creación de roles iniciales
INSERT INTO user_roles (name, description)
VALUES
  ('admin', 'Administrador del sistema'),
  ('user', 'Usuario del sistema');

