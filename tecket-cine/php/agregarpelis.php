<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "venta_ticket_cine");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Recoger datos del formulario
$titulo = $_POST['titulo'];
$duracion = $_POST['duracion'];
$genero = $_POST['genero'];
$clasificacion = $_POST['clasificacion'];
$sinopsis = $_POST['sinopsis'];
$fecha_estreno = $_POST['fecha_estreno'];
$director = $_POST['director'];

// Procesar imagen si fue subida
$imagen_poster = null;
if (isset($_FILES['imagen_poster']) && $_FILES['imagen_poster']['tmp_name'] != "") {
    $imagen_poster = addslashes(file_get_contents($_FILES['imagen_poster']['tmp_name']));
}

// Preparar la consulta
$sql = "INSERT INTO Pelicula (titulo, duracion, genero, clasificacion, sinopsis, imagen_poster, fecha_estreno, director) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);

// Revisar si la preparación fue exitosa
if (!$stmt) {
    die("Error en la preparación: " . $conexion->error);
}

// Enviar los datos
$stmt->bind_param("sissssss", 
    $titulo, 
    $duracion, 
    $genero, 
    $clasificacion, 
    $sinopsis, 
    $fecha_estreno, 
    $director,
    $imagen_poster
);

// Enviar imagen binaria (campo largo)
if ($imagen_poster) {
    $stmt->send_long_data(5, $imagen_poster);
}

// Ejecutar y redirigir
if ($stmt->execute()) {
    header("Location: panel.php");
    exit();
} else {
    echo "Error al guardar película: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
