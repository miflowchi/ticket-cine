-- Creación de la base de datos
CREATE DATABASE IF NOT EXISTS cine_tickets;
USE cine_tickets;

-- Tabla de administradores
CREATE TABLE administradores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    nivel_acceso INT DEFAULT 1
);

-- Insertar administrador por defecto (contraseña: admin123, texto plano)
INSERT INTO administradores (nombre, email, password, nivel_acceso) VALUES
('Administrador', 'admin123@gmail.com', 'admin123', 2);

-- Tabla de películas
CREATE TABLE peliculas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    duracion INT, -- en minutos
    genero VARCHAR(50),
    clasificacion VARCHAR(10),
    director VARCHAR(100),
    actores TEXT,
    imagen_portada VARCHAR(255),
    fecha_estreno DATE,
    precio_base DECIMAL(10,2) NOT NULL,
    estado ENUM('estreno', 'cartelera', 'proximo') DEFAULT 'cartelera',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de salas
CREATE TABLE salas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    capacidad INT NOT NULL,
    tipo_sala ENUM('2D', '3D', '4DX', 'VIP') DEFAULT '2D',
    descripcion TEXT
);

-- Salas predefinidas
INSERT INTO salas (nombre, capacidad, tipo_sala, descripcion) VALUES
('Sala A', 80, '2D', 'Sala estándar'),
('Sala B', 100, '3D', 'Sala 3D'),
('Sala C', 60, 'VIP', 'Sala VIP'),
('Sala D', 120, '2D', 'Sala grande'),
('Sala E', 90, '4DX', 'Sala 4DX');

-- Tabla de funciones (película + sala + horario)
CREATE TABLE funciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pelicula_id INT NOT NULL,
    sala_id INT NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    precio_especial DECIMAL(10,2),
    asientos_disponibles INT NOT NULL,
    FOREIGN KEY (pelicula_id) REFERENCES peliculas(id),
    FOREIGN KEY (sala_id) REFERENCES salas(id),
    UNIQUE KEY (sala_id, fecha, hora)
);

-- Tabla de tickets
CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    funcion_id INT NOT NULL,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    precio_total DECIMAL(10,2) NOT NULL,
    metodo_pago ENUM('tarjeta', 'yape') NOT NULL,
    estado ENUM('pendiente', 'pagado', 'usado', 'cancelado') DEFAULT 'pendiente',
    fecha_compra DATETIME DEFAULT CURRENT_TIMESTAMP,
    cliente_nombre VARCHAR(100),
    cliente_email VARCHAR(100),
    FOREIGN KEY (funcion_id) REFERENCES funciones(id)
);

-- Tabla de asientos (para control más detallado)
CREATE TABLE asientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sala_id INT NOT NULL,
    fila CHAR(1) NOT NULL,
    numero INT NOT NULL,
    tipo ENUM('normal', 'preferencial', 'discapacitados') DEFAULT 'normal',
    FOREIGN KEY (sala_id) REFERENCES salas(id),
    UNIQUE KEY (sala_id, fila, numero)
);

-- Tabla de asientos reservados
CREATE TABLE asientos_reservados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT NOT NULL,
    asiento_id INT NOT NULL,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id),
    FOREIGN KEY (asiento_id) REFERENCES asientos(id)
);