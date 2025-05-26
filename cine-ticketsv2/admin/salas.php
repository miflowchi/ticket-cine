<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// Solo administradores
protectAdminRoute();

// Obtener salas y películas
$salas = $db->query("SELECT * FROM salas ORDER BY nombre");
$peliculas = $db->query("SELECT id, titulo FROM peliculas WHERE estado='cartelera' ORDER BY titulo");

// Procesar asignación de película a sala (crear función)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sala_id = (int)$_POST['sala_id'];
    $pelicula_id = (int)$_POST['pelicula_id'];
    $fecha = $db->escape($_POST['fecha']);
    $hora = $db->escape($_POST['hora']);
    $precio_especial = isset($_POST['precio_especial']) && $_POST['precio_especial'] !== '' ? (float)$_POST['precio_especial'] : 'NULL';

    // Obtener capacidad de la sala
    $capacidad = 0;
    $cap = $db->query("SELECT capacidad FROM salas WHERE id = $sala_id");
    if ($row = $cap->fetch_assoc()) {
        $capacidad = (int)$row['capacidad'];
    }

    // Insertar función
    $query = "INSERT INTO funciones (pelicula_id, sala_id, fecha, hora, precio_especial, asientos_disponibles)
              VALUES ($pelicula_id, $sala_id, '$fecha', '$hora', $precio_especial, $capacidad)";
    if ($db->query($query)) {
        $success = "Película asignada a la sala correctamente.";
    } else {
        $error = "Error al asignar película. Verifica que no exista una función igual para la misma sala, fecha y hora.";
    }
}

// Mostrar funciones por sala
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
    <title>Salas - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-container">
    <?php include 'includes/sidebar.php'; ?>
    <main class="admin-main">
        <h1>Asignar Película a Sala</h1>
        <?php if (isset($success)): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" class="form-pelicula">
            <div class="form-row">
                <div class="form-group">
                    <label for="sala_id">Sala</label>
                    <select name="sala_id" id="sala_id" required>
                        <option value="">Seleccionar</option>
                        <?php foreach($salas as $sala): ?>
                            <option value="<?php echo $sala['id']; ?>"><?php echo $sala['nombre']; ?> (Capacidad: <?php echo $sala['capacidad']; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="pelicula_id">Película</label>
                    <select name="pelicula_id" id="pelicula_id" required>
                        <option value="">Seleccionar</option>
                        <?php foreach($peliculas as $p): ?>
                            <option value="<?php echo $p['id']; ?>"><?php echo $p['titulo']; ?></option>
                        <?php endforeach; ?>
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
            <button type="submit" class="btn">Asignar Película</button>
        </form>

        <h2>Funciones por Sala</h2>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Sala</th>
                        <th>Película</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Precio Especial</th>
                        <th>Asientos Disponibles</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($f = $funciones->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $f['sala_nombre']; ?></td>
                            <td><?php echo $f['titulo']; ?></td>
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
