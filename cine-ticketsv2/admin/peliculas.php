<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// Verificar si el usuario es administrador
if (!isAdmin()) {
    header("Location: ../login.php");
    exit();
}

// Procesar formulario de agregar/editar película
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $titulo = $db->escape($_POST['titulo']);
    $descripcion = $db->escape($_POST['descripcion']);
    $duracion = (int)$_POST['duracion'];
    $genero = $db->escape($_POST['genero']);
    $clasificacion = $db->escape($_POST['clasificacion']);
    $director = $db->escape($_POST['director']);
    $actores = $db->escape($_POST['actores']);
    $precio_base = (float)$_POST['precio_base'];
    $estado = $db->escape($_POST['estado']);
    $fecha_estreno = $db->escape($_POST['fecha_estreno']);
    
    // Manejar la imagen
    $imagen_portada = '';
    if (isset($_FILES['imagen_portada']) && $_FILES['imagen_portada']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['imagen_portada']['name'], PATHINFO_EXTENSION);
        $imagen_portada = uniqid() . '.' . $ext;
        $rutaImagen = "../assets/images/$imagen_portada";
        // Crear la carpeta si no existe
        if (!is_dir("../assets/images")) {
            mkdir("../assets/images", 0777, true);
        }
        move_uploaded_file($_FILES['imagen_portada']['tmp_name'], $rutaImagen);
        
        // Si es una edición y se subió nueva imagen, borrar la anterior
        if ($id > 0) {
            $oldImageQuery = $db->query("SELECT imagen_portada FROM peliculas WHERE id = $id");
            if ($oldImage = $oldImageQuery->fetch_assoc()) {
                if (file_exists("../assets/images/{$oldImage['imagen_portada']}")) {
                    unlink("../assets/images/{$oldImage['imagen_portada']}");
                }
            }
        }
    } elseif ($id > 0) {
        // Mantener la imagen existente si no se subió una nueva
        $oldImageQuery = $db->query("SELECT imagen_portada FROM peliculas WHERE id = $id");
        $imagen_portada = $oldImageQuery->fetch_assoc()['imagen_portada'];
    }
    
    if ($id > 0) {
        // Actualizar película existente
        $query = "UPDATE peliculas SET 
                  titulo = '$titulo', 
                  descripcion = '$descripcion', 
                  duracion = $duracion, 
                  genero = '$genero', 
                  clasificacion = '$clasificacion', 
                  director = '$director', 
                  actores = '$actores', 
                  imagen_portada = '$imagen_portada', 
                  precio_base = $precio_base, 
                  estado = '$estado', 
                  fecha_estreno = '$fecha_estreno' 
                  WHERE id = $id";
    } else {
        // Insertar nueva película
        $query = "INSERT INTO peliculas (titulo, descripcion, duracion, genero, clasificacion, director, actores, imagen_portada, precio_base, estado, fecha_estreno) 
                  VALUES ('$titulo', '$descripcion', $duracion, '$genero', '$clasificacion', '$director', '$actores', '$imagen_portada', $precio_base, '$estado', '$fecha_estreno')";
    }
    
    if ($db->query($query)) {
        $success = "Película " . ($id > 0 ? "actualizada" : "agregada") . " correctamente.";
    } else {
        $error = "Error al " . ($id > 0 ? "actualizar" : "agregar") . " la película.";
    }
}

// Procesar eliminación de película
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Obtener imagen para borrarla
    $imageQuery = $db->query("SELECT imagen_portada FROM peliculas WHERE id = $id");
    if ($image = $imageQuery->fetch_assoc()) {
        if (file_exists("../assets/images/{$image['imagen_portada']}")) {
            unlink("../assets/images/{$image['imagen_portada']}");
        }
    }
    
    $deleteQuery = "DELETE FROM peliculas WHERE id = $id";
    if ($db->query($deleteQuery)) {
        $success = "Película eliminada correctamente.";
    } else {
        $error = "Error al eliminar la película.";
    }
}

// Obtener película para edición
$peliculaEdit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $query = "SELECT * FROM peliculas WHERE id = $id";
    $result = $db->query($query);
    $peliculaEdit = $result->fetch_assoc();
}

// Obtener todas las películas
$peliculasQuery = "SELECT * FROM peliculas ORDER BY fecha_estreno DESC";
$peliculasResult = $db->query($peliculasQuery);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Películas - <?php echo SITE_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="admin-main">
            <h1><?php echo isset($peliculaEdit) ? 'Editar Película' : 'Agregar Película'; ?></h1>
            
            <?php if (isset($success)): ?>
                <div class="alert success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" class="form-pelicula">
                <?php if (isset($peliculaEdit)): ?>
                    <input type="hidden" name="id" value="<?php echo $peliculaEdit['id']; ?>">
                <?php endif; ?>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="titulo">Título</label>
                        <input type="text" name="titulo" id="titulo" required 
                               value="<?php echo isset($peliculaEdit) ? $peliculaEdit['titulo'] : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="genero">Género</label>
                        <select name="genero" id="genero" required>
                            <option value="">Seleccionar</option>
                            <option value="Acción" <?php echo (isset($peliculaEdit) && $peliculaEdit['genero'] === 'Acción' ? 'selected' : ''); ?>>Acción</option>
                            <option value="Comedia" <?php echo (isset($peliculaEdit) && $peliculaEdit['genero'] === 'Comedia' ? 'selected' : ''); ?>>Comedia</option>
                            <option value="Drama" <?php echo (isset($peliculaEdit) && $peliculaEdit['genero'] === 'Drama' ? 'selected' : ''); ?>>Drama</option>
                            <option value="Terror" <?php echo (isset($peliculaEdit) && $peliculaEdit['genero'] === 'Terror' ? 'selected' : ''); ?>>Terror</option>
                            <option value="Ciencia Ficción" <?php echo (isset($peliculaEdit) && $peliculaEdit['genero'] === 'Ciencia Ficción' ? 'selected' : ''); ?>>Ciencia Ficción</option>
                            <option value="Animación" <?php echo (isset($peliculaEdit) && $peliculaEdit['genero'] === 'Animación' ? 'selected' : ''); ?>>Animación</option>
                            <option value="Romance" <?php echo (isset($peliculaEdit) && $peliculaEdit['genero'] === 'Romance' ? 'selected' : ''); ?>>Romance</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3" required><?php echo isset($peliculaEdit) ? $peliculaEdit['descripcion'] : ''; ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="duracion">Duración (minutos)</label>
                        <input type="number" name="duracion" id="duracion" min="60" max="240" required 
                               value="<?php echo isset($peliculaEdit) ? $peliculaEdit['duracion'] : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="clasificacion">Clasificación</label>
                        <select name="clasificacion" id="clasificacion" required>
                            <option value="">Seleccionar</option>
                            <option value="A" <?php echo (isset($peliculaEdit) && $peliculaEdit['clasificacion'] === 'A' ? 'selected' : ''); ?>>A (Todo público)</option>
                            <option value="B" <?php echo (isset($peliculaEdit) && $peliculaEdit['clasificacion'] === 'B' ? 'selected' : ''); ?>>B (+12 años)</option>
                            <option value="C" <?php echo (isset($peliculaEdit) && $peliculaEdit['clasificacion'] === 'C' ? 'selected' : ''); ?>>C (+15 años)</option>
                            <option value="D" <?php echo (isset($peliculaEdit) && $peliculaEdit['clasificacion'] === 'D' ? 'selected' : ''); ?>>D (+18 años)</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="director">Director</label>
                        <input type="text" name="director" id="director" required 
                               value="<?php echo isset($peliculaEdit) ? $peliculaEdit['director'] : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="actores">Actores principales</label>
                        <input type="text" name="actores" id="actores" required 
                               value="<?php echo isset($peliculaEdit) ? $peliculaEdit['actores'] : ''; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="precio_base">Precio base (S/.)</label>
                        <input type="number" name="precio_base" id="precio_base" min="10" max="50" step="0.01" required 
                               value="<?php echo isset($peliculaEdit) ? $peliculaEdit['precio_base'] : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <select name="estado" id="estado" required>
                            <option value="proximo" <?php echo (isset($peliculaEdit) && $peliculaEdit['estado'] === 'proximo' ? 'selected' : ''); ?>>Próximo estreno</option>
                            <option value="estreno" <?php echo (isset($peliculaEdit) && $peliculaEdit['estado'] === 'estreno' ? 'selected' : ''); ?>>Estreno</option>
                            <option value="cartelera" <?php echo (isset($peliculaEdit) && $peliculaEdit['estado'] === 'cartelera' ? 'selected' : ''); ?>>En cartelera</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="fecha_estreno">Fecha de estreno</label>
                        <input type="date" name="fecha_estreno" id="fecha_estreno" required 
                               value="<?php echo isset($peliculaEdit) ? $peliculaEdit['fecha_estreno'] : ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="imagen_portada">Imagen de portada</label>
                    <input type="file" name="imagen_portada" id="imagen_portada" <?php echo !isset($peliculaEdit) ? 'required' : ''; ?>>
                    <?php if (isset($peliculaEdit) && !empty($peliculaEdit['imagen_portada'])): ?>
                        <p>Imagen actual: <a href="../assets/images/<?php echo $peliculaEdit['imagen_portada']; ?>" target="_blank">Ver</a></p>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="btn"><?php echo isset($peliculaEdit) ? 'Actualizar' : 'Agregar'; ?> Película</button>
                
                <?php if (isset($peliculaEdit)): ?>
                    <a href="peliculas.php" class="btn cancelar">Cancelar</a>
                <?php endif; ?>
            </form>
            
            <h2>Lista de Películas</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Título</th>
                            <th>Género</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($pelicula = $peliculasResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $pelicula['id']; ?></td>
                            <td><img src="../assets/images/<?php echo $pelicula['imagen_portada']; ?>" alt="<?php echo $pelicula['titulo']; ?>" class="table-image"></td>
                            <td><?php echo $pelicula['titulo']; ?></td>
                            <td><?php echo $pelicula['genero']; ?></td>
                            <td>S/. <?php echo number_format($pelicula['precio_base'], 2); ?></td>
                            <td><?php echo ucfirst($pelicula['estado']); ?></td>
                            <td>
                                <a href="peliculas.php?edit=<?php echo $pelicula['id']; ?>" class="btn small">Editar</a>
                                <a href="peliculas.php?delete=<?php echo $pelicula['id']; ?>" class="btn small danger" onclick="return confirm('¿Estás seguro de eliminar esta película?');">Eliminar</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script src="../assets/js/admin.js"></script>
</body>
</html>