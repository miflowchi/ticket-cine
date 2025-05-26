<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

// Verificar que se haya seleccionado una película
if (!isset($_GET['pelicula'])) {
    header("Location: cartelera.php");
    exit();
}

$peliculaId = (int)$_GET['pelicula'];

// Obtener información de la película
$peliculaQuery = "SELECT * FROM peliculas WHERE id = $peliculaId";
$peliculaResult = $db->query($peliculaQuery);

if ($peliculaResult->num_rows === 0) {
    header("Location: cartelera.php");
    exit();
}

$pelicula = $peliculaResult->fetch_assoc();

// Obtener funciones disponibles para esta película
$funcionesQuery = "SELECT f.*, s.nombre as sala_nombre, s.tipo_sala 
                   FROM funciones f 
                   JOIN salas s ON f.sala_id = s.id 
                   WHERE f.pelicula_id = $peliculaId 
                   AND f.fecha >= CURDATE() 
                   ORDER BY f.fecha, f.hora";
$funcionesResult = $db->query($funcionesQuery);

$funciones = [];
while($f = $funcionesResult->fetch_assoc()) {
    $funciones[] = $f;
}

// Procesar compra
$compraExitosa = false;
$ticketCodigo = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $funcion_id = (int)$_POST['funcion_id'];
    $cantidad = (int)$_POST['cantidad'];
    $tipo_ticket = $_POST['tipo_ticket'];
    $nombre = $db->escape($_POST['nombre']);
    $email = $db->escape($_POST['email']);
    $metodo_pago = $_POST['metodo_pago'];

    // Obtener datos de la función seleccionada
    $funcionSel = null;
    foreach ($funciones as $f) {
        if ($f['id'] == $funcion_id) {
            $funcionSel = $f;
            break;
        }
    }

    if (!$funcionSel) {
        $error = "Función seleccionada no válida.";
    } elseif ($cantidad < 1 || $cantidad > 10) {
        $error = "La cantidad debe ser entre 1 y 10 tickets.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Ingrese un email válido.";
    } elseif ($funcionSel['asientos_disponibles'] < $cantidad) {
        $error = "No hay suficientes asientos disponibles.";
    } else {
        // Precio base
        $precio_unitario = $funcionSel['precio_especial'] ?? $pelicula['precio_base'];
        // Puedes ajustar el precio según el tipo de ticket si lo deseas
        $precio_total = $precio_unitario * $cantidad;

        // Generar código único
        $codigo = strtoupper(uniqid('TICKET'));

        // Insertar ticket
        $insert = $db->query("INSERT INTO tickets (funcion_id, codigo, cantidad, precio_unitario, precio_total, metodo_pago, cliente_nombre, cliente_email) 
                              VALUES ($funcion_id, '$codigo', $cantidad, $precio_unitario, $precio_total, '$metodo_pago', '$nombre', '$email')");
        if ($insert) {
            // Actualizar asientos disponibles
            $db->query("UPDATE funciones SET asientos_disponibles = asientos_disponibles - $cantidad WHERE id = $funcion_id");
            $compraExitosa = true;
            $ticketCodigo = $codigo;
        } else {
            $error = "Error al procesar la compra. Intente nuevamente.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar Ticket - <?php echo $pelicula['titulo']; ?> | <?php echo SITE_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <h1><?php echo SITE_NAME; ?></h1>
            </div>
            <nav class="nav">
                <a href="index.php">Inicio</a>
                <a href="cartelera.php">Cartelera</a>
                <a href="login.php">Admin</a>
            </nav>
        </div>
    </header>

    <main class="main">
        <section class="compra-ticket">
            <div class="container">
                <div class="pelicula-info">
                    <img src="assets/images/<?php echo $pelicula['imagen_portada']; ?>" alt="<?php echo $pelicula['titulo']; ?>">
                    <div class="info-text">
                        <h2><?php echo $pelicula['titulo']; ?></h2>
                        <p><strong>Género:</strong> <?php echo $pelicula['genero']; ?></p>
                        <p><strong>Duración:</strong> <?php echo $pelicula['duracion']; ?> minutos</p>
                        <p><strong>Clasificación:</strong> <?php echo $pelicula['clasificacion']; ?></p>
                        <p><strong>Director:</strong> <?php echo $pelicula['director']; ?></p>
                        <p><strong>Actores:</strong> <?php echo $pelicula['actores']; ?></p>
                        <p><?php echo $pelicula['descripcion']; ?></p>
                    </div>
                </div>

                <?php if ($compraExitosa): ?>
                    <div class="alert success" style="margin:30px 0;">
                        ¡Compra exitosa! Tu ticket ha sido generado.
                        <br>
                        <a href="ticket.php?codigo=<?php echo $ticketCodigo; ?>" class="btn" style="margin-top:15px;">Ver Ticket</a>
                    </div>
                <?php else: ?>
                    <?php if (isset($error)): ?>
                        <div class="alert error"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST" class="formulario-compra" style="margin-top:40px;">
                        <h3>Comprar Ticket</h3>
                        <div class="form-group">
                            <label for="funcion_id">Función (fecha, hora y sala)</label>
                            <select name="funcion_id" id="funcion_id" required>
                                <option value="">Selecciona una función</option>
                                <?php foreach($funciones as $f): ?>
                                    <option value="<?php echo $f['id']; ?>">
                                        <?php echo date('d/m/Y', strtotime($f['fecha'])) . ' ' . date('H:i', strtotime($f['hora'])) . ' - Sala ' . $f['sala_nombre'] . ' (' . $f['tipo_sala'] . ')'; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cantidad">Cantidad de tickets</label>
                            <select name="cantidad" id="cantidad" required>
                                <?php for($i = 1; $i <= 10; $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tipo_ticket">Tipo de ticket</label>
                            <select name="tipo_ticket" id="tipo_ticket" required>
                                <option value="adulto">Adulto</option>
                                <option value="niño">Niño</option>
                                <option value="adulto mayor">Adulto mayor</option>
                                <option value="discapacitado">Discapacitado</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre completo</label>
                            <input type="text" name="nombre" id="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Correo electrónico</label>
                            <input type="email" name="email" id="email" required>
                        </div>
                        <div class="form-group">
                            <label>Método de pago</label>
                            <div class="metodos-pago">
                                <label>
                                    <input type="radio" name="metodo_pago" value="tarjeta" checked>
                                    <span>Tarjeta de crédito/débito</span>
                                </label>
                                <label>
                                    <input type="radio" name="metodo_pago" value="yape">
                                    <span>Yape</span>
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn">Confirmar compra</button>
                    </form>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>