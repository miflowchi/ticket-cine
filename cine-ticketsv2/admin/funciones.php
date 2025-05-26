<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// Solo administradores
protectAdminRoute();

// Obtener películas y salas para los selects
$peliculas = $db->query("SELECT id, titulo FROM peliculas ORDER BY titulo");
$salas = $db->query("SELECT id, nombre, capacidad FROM salas ORDER BY nombre");

// Procesar formulario de agregar función
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pelicula_id = (int)$_POST['pelicula_id'];
    $sala_id = (int)$_POST['sala_id'];
    $fecha = $db->escape($_POST['fecha']);
    $hora = $db->escape($_POST['hora']);
    $precio_especial = isset($_POST['precio_especial']) && $_POST['precio_especial'] !== '' ? (float)$_POST['precio_especial'] : 'NULL';

    // Obtener capacidad de la sala
    $capacidadSala = 0;
    $salaCap = $db->query("SELECT capacidad FROM salas WHERE id = $sala_id");
    if ($row = $salaCap->fetch_assoc()) {
        $capacidadSala = (int)$row['capacidad'];
    }

    // Insertar función
    $query = "INSERT INTO funciones (pelicula_id, sala_id, fecha, hora, precio_especial, asientos_disponibles)
              VALUES ($pelicula_id, $sala_id, '$fecha', '$hora', $precio_especial, $capacidadSala)";
    if ($db->query($query)) {
        $success = "Función agregada correctamente.";
    } else {
        $error = "Error al agregar la función. Verifica que no exista una función igual para la misma sala, fecha y hora.";
    }
}

// Obtener todas las funciones
$funciones = $db->query("SELECT f.*, p.titulo, s.nombre as sala_nombre
                         FROM funciones f
                         JOIN peliculas p ON f.pelicula_id = p.id
                         JOIN salas s ON f.sala_id = s.id
                         ORDER BY f.fecha DESC, f.hora DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Funciones - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-container">
    <?php include 'includes/sidebar.php'; ?>
    <main class="admin-main">
        <h1>Funciones</h1>
        <?php if (isset($success)): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" class="form-pelicula">
            <div class="form-row">
                <div class="form-group">
                    <label for="pelicula_id">Película</label>
                    <select name="pelicula_id" id="pelicula_id" required>
                        <option value="">Seleccionar</option>
                        <?php while($p = $peliculas->fetch_assoc()): ?>
                            <option value="<?php echo $p['id']; ?>"><?php echo $p['titulo']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="sala_id">Sala</label>
                    <select name="sala_id" id="sala_id" required>
                        <option value="">Seleccionar</option>
                        <?php while($s = $salas->fetch_assoc()): ?>
                            <option value="<?php echo $s['id']; ?>"><?php echo $s['nombre']; ?> (Capacidad: <?php echo $s['capacidad']; ?>)</option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="fecha">Fecha</label>
                    <input type="date" name="fecha" id="fecha" required>
                </div>
                <div class="form-group">
                    <label for="hora">Hora</label>
                    <input type="time" name="hora" id="hora" required>
                </div>
                <div class="form-group">
                    <label for="precio_especial">Precio especial (opcional)</label>
                    <input type="number" name="precio_especial" id="precio_especial" min="0" step="0.01">
                </div>
            </div>
            <button type="submit" class="btn">Agregar Función</button>
        </form>

        <h2>Lista de Funciones</h2>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Película</th>
                        <th>Sala</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Precio Especial</th>
                        <th>Asientos Disponibles</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($f = $funciones->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $f['titulo']; ?></td>
                            <td><?php echo $f['sala_nombre']; ?></td>
                            <td><?php echo $f['fecha']; ?></td>
                            <td><?php echo substr($f['hora'], 0, 5); ?></td>
                            <td><?php echo $f['precio_especial'] !== null ? 'S/. '.number_format($f['precio_especial'],2) : '-'; ?></td>
                            <td><?php echo $f['asientos_disponibles']; ?></td>
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
