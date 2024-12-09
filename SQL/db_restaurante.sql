CREATE DATABASE db_restaurante;

USE db_restaurante;

CREATE TABLE roles(
    id_rol INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre_rol VARCHAR(20) NOT NULL
);
CREATE TABLE tbl_user(
    id_user INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    surname VARCHAR(30) NOT NULL,
    username VARCHAR(30) NOT NULL,
    user_pwd CHAR(64) NOT NULL,
    rol_user INT NOT NULL
);
CREATE TABLE tipo_salas(
    id_tipo_sala INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre_tipo_sala VARCHAR(50) NOT NULL
);
CREATE TABLE tbl_salas(
    id_salas INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name_sala VARCHAR(15) NOT NULL,
    tipo_sala INT NOT NULL
);
CREATE TABLE tbl_mesas(
    id_mesa INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    n_asientos INT NOT NULL,
    id_sala INT NOT NULL
);
CREATE TABLE ocupacion(
    id_ocupacion INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fecha_C DATETIME NOT NULL,
    fecha_F DATETIME NULL,
    assigned_by INT NOT NULL,
    assigned_to VARCHAR(30) NOT NULL,
    es_reserva BOOLEAN NOT NULL DEFAULT FALSE,
    id_mesa INT NOT NULL
);

ALTER TABLE
    ocupacion ADD CONSTRAINT ocupacion_id_mesa_foreign FOREIGN KEY(id_mesa) REFERENCES tbl_mesas(id_mesa);
ALTER TABLE
    tbl_mesas ADD CONSTRAINT tbl_mesas_id_sala_foreign FOREIGN KEY(id_sala) REFERENCES tbl_salas(id_salas);
ALTER TABLE
    tbl_salas ADD CONSTRAINT tbl_salas_tipo_sala_foreign FOREIGN KEY(tipo_sala) REFERENCES tipo_salas(id_tipo_sala);
ALTER TABLE
    ocupacion ADD CONSTRAINT ocupacion_assigned_by_foreign FOREIGN KEY(assigned_by) REFERENCES tbl_user(id_user);
ALTER TABLE
    tbl_user ADD CONSTRAINT tbl_user_rol_user_foreign FOREIGN KEY(rol_user) REFERENCES roles(id_rol);


-- Inserciones en la tabla roles
INSERT INTO roles (nombre_rol)
VALUES 
('Administrador'),
('Camarero');

-- Inserciones en la tabla tbl_user
INSERT INTO tbl_user (name, surname, username, user_pwd, rol_user)
VALUES
('Richard', 'Owells', 'rowells', SHA2('Administrador123.', 256), 1),
('Carlos', 'García', 'cgarcia', SHA2('Camarero123.', 256), 2),
('Laura', 'Martínez', 'lmartinez', SHA2('Camarero123.', 256), 2),
('Ana', 'Sánchez', 'asanchez', SHA2('Camarero123.', 256), 2),
('Jorge', 'Hernández', 'jhernandez', SHA2('Camarero123.', 256), 2),
('Elena', 'López', 'elopez', SHA2('Camarero123.', 256), 2);

-- Inserciones en la tabla tipo_salas
INSERT INTO tipo_salas (nombre_tipo_sala)
VALUES 
('Comedor'),
('Terraza'),
('VIP');

-- Inserciones en la tabla tbl_salas
INSERT INTO tbl_salas (name_sala, tipo_sala)
VALUES
('Comedor_1', 1),
('Terraza_1', 2),
('Salon_VIP', 3),
('Comedor_2', 1),
('Jardin', 2),
('Terraza_2', 2),
('Salon_VIP_2', 3),
('Salon_romantico', 3),
('Naturaleza', 3);

-- Inserciones en la tabla tbl_mesas
INSERT INTO tbl_mesas (n_asientos, id_sala) 
VALUES
-- Comedor_1 
(6, 1),
(6, 1),
(4, 1),
(4, 1),
-- Comedor_2 
(6, 4),
(6, 4),
(4, 4),
(4, 4),
-- Naturaleza 
(2, 9),
(2, 9),
(2, 9),
-- Salon_VIP 
(2, 3),
(2, 3),
(2, 3),
-- Salon_VIP_2 
(2, 7),
(2, 7),
(2, 7),
-- Salon_romantico 
(2, 8),
-- Terraza_1 
(2, 2),
(4, 2),
(4, 2),
-- Terraza_2 
(6, 6),
(4, 6),
-- Jardin 
(4, 5),
(4, 5),
(2, 5),
(2, 5),
(6, 5);

-- Inserciones en la tabla ocupacion
INSERT INTO ocupacion (fecha_C, fecha_F, assigned_by, assigned_to, es_reserva, id_mesa)
VALUES 
(NOW(), NULL, 1, 'Hugo', FALSE, 1),
(NOW(), NULL, 5, 'Alex', FALSE, 2),
(NOW(), NULL, 2, 'Erik', FALSE, 3),
(NOW(), NULL, 4, 'Ming', FALSE, 4),
(NOW(), DATE_ADD(NOW(), INTERVAL 1 MONTH), 3, 'Dylan', TRUE, 4);
