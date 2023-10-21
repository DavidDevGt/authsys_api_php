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

-- Permisos para el rol admin
INSERT INTO permissions (role_id, permission) VALUES (1, 'edit_user');
INSERT INTO permissions (role_id, permission) VALUES (1, 'delete_user');
INSERT INTO permissions (role_id, permission) VALUES (1, 'assign_role');
INSERT INTO permissions (role_id, permission) VALUES (1, 'revoke_role');
INSERT INTO permissions (role_id, permission) VALUES (1, 'create_role');
INSERT INTO permissions (role_id, permission) VALUES (1, 'edit_role');
INSERT INTO permissions (role_id, permission) VALUES (1, 'delete_role');
INSERT INTO permissions (role_id, permission) VALUES (1, 'assign_permission');
INSERT INTO permissions (role_id, permission) VALUES (1, 'revoke_permission');

-- Permisos para el rol user
INSERT INTO permissions (role_id, permission) VALUES (2, 'view_profile');
INSERT INTO permissions (role_id, permission) VALUES (2, 'edit_profile');

-- Insertar usuario admin
INSERT INTO user_auth (email, password, is_verified)
VALUES ('admin@admin.com', 'admin123', TRUE);

-- Asignar el rol admin al usuario admin
INSERT INTO user_role_assignments (user_auth_id, role_id)
VALUES (LAST_INSERT_ID(), 1);
