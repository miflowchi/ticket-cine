    -- Creación de la base de datos
CREATE DATABASE venta_ticket_cine;
USE venta_ticket_cine;

-- Tabla de Administradores
CREATE TABLE Administrador (
    admin_id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,  -- Se recomienda almacenar contraseñas hasheadas
    foto_perfil LONGBLOB,  -- Para almacenar la foto como binario (opcional)
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso DATETIME,
    activo BOOLEAN DEFAULT TRUE
);

-- Tabla de Usuarios
CREATE TABLE Usuario (
    usuario_id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,  -- Se recomienda almacenar contraseñas hasheadas
    foto_perfil LONGBLOB,  -- Para almacenar la foto como binario (opcional)
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_nacimiento DATE,
    telefono VARCHAR(20),
    activo BOOLEAN DEFAULT TRUE
);

-- Tabla de Películas
CREATE TABLE Pelicula (
    pelicula_id INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(255) NOT NULL,
    duracion INT NOT NULL,  -- en minutos
    genero VARCHAR(100),
    clasificacion VARCHAR(50),
    sinopsis TEXT,
    imagen_poster LONGBLOB,  -- Imagen del poster (opcional)
    fecha_estreno DATE,
    director VARCHAR(100),
    activa BOOLEAN DEFAULT TRUE
);

-- Tabla de Salas
CREATE TABLE Sala (
    sala_id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    capacidad INT NOT NULL,
    tipo_sala VARCHAR(50),  -- Ej: 2D, 3D, IMAX, etc.
    descripcion TEXT
);

-- Tabla de Funciones (horarios de películas)
CREATE TABLE Funcion (
    funcion_id INT PRIMARY KEY AUTO_INCREMENT,
    pelicula_id INT NOT NULL,
    sala_id INT NOT NULL,
    fecha_hora DATETIME NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    asientos_disponibles INT NOT NULL,
    FOREIGN KEY (pelicula_id) REFERENCES Pelicula(pelicula_id),
    FOREIGN KEY (sala_id) REFERENCES Sala(sala_id)
);

-- Tabla de Tickets
CREATE TABLE Ticket (
    ticket_id INT PRIMARY KEY AUTO_INCREMENT,
    funcion_id INT NOT NULL,
    usuario_id INT,
    admin_id INT,  -- Si fue vendido por un administrador
    fecha_compra TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    asiento VARCHAR(10) NOT NULL,  -- Ej: "A12", "B5", etc.
    precio DECIMAL(10,2) NOT NULL,
    metodo_pago VARCHAR(50),
    codigo_barras VARCHAR(100) UNIQUE,  -- Para escanear el ticket
    estado ENUM('reservado', 'pagado', 'usado', 'cancelado') DEFAULT 'pagado',
    FOREIGN KEY (funcion_id) REFERENCES Funcion(funcion_id),
    FOREIGN KEY (usuario_id) REFERENCES Usuario(usuario_id),
    FOREIGN KEY (admin_id) REFERENCES Administrador(admin_id)
);

-- Tabla de OCR (si necesitas almacenar información de reconocimiento óptico)
CREATE TABLE OCR_Data (
    ocr_id INT PRIMARY KEY AUTO_INCREMENT,
    ticket_id INT,
    texto_reconocido TEXT,
    fecha_procesamiento DATETIME DEFAULT CURRENT_TIMESTAMP,
    tipo_documento VARCHAR(50),
    FOREIGN KEY (ticket_id) REFERENCES Ticket(ticket_id)
);