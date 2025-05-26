<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

// Verificar que se haya seleccionado una función
if (!isset($_GET['funcion'])) {
    header("Location: cartelera.php");
    exit();
}

$funcionId = (int)$_GET['funcion'];

// Obtener información de la función
$funcionQuery = "SELECT f.*, p.titulo, p.imagen_portada, p.precio_base, s.nombre as sala_nombre 
                 FROM funciones f 
                 JOIN peliculas p ON f.pelicula_id = p.id 
                 JOIN salas s ON f.sala_id = s.id 
                 WHERE f.id = $funcionId";
$funcionResult = $db->query($funcionQuery);

if ($funcionResult->num_rows === 0) {
    header("Location: cartelera.php");
    exit();
}

$funcion = $funcionResult->fetch_assoc();

// Calcular precio a pagar
$precio = $funcion['precio_especial'] ?? $funcion['precio_base'];

// Procesar el formulario de compra
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cantidad = (int)$_POST['cantidad'];
    $metodoPago = $_POST['metodo_pago'];
    $nombre = $db->escape($_POST['nombre']);
    $email = $db->escape($_POST['email']);
    
    // Validaciones
    if ($cantidad < 1 || $cantidad > 10) {
        $error = "La cantidad debe ser entre 1 y 10 tickets.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Ingrese un email válido.";
    } else {
        // Calcular total
        $total = $cantidad * $precio;
        
        // Generar código único
        $codigo = strtoupper(uniqid('TICKET'));
        
        // Insertar ticket en la base de datos
        $insertQuery = "INSERT INTO tickets (funcion_id, codigo, cantidad, precio_unitario, precio_total, metodo_pago, cliente_nombre, cliente_email) 
                         VALUES ($funcionId, '$codigo', $cantidad, $precio, $total, '$metodoPago', '$nombre', '$email')";
        
        if ($db->query($insertQuery)) {
            // Redirigir a la página del ticket
            header("Location: ticket.php?codigo=$codigo");
            exit();
        } else {
            $error = "Error al procesar la compra. Por favor intente nuevamente.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago - <?php echo $funcion['titulo']; ?> | <?php echo SITE_NAME; ?></title>
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
        <section class="pago-ticket">
            <div class="container">
                <div class="resumen-compra">
                    <h2>Resumen de compra</h2>
                    <div class="pelicula-info">
                        <img src="assets/images/<?php echo $funcion['imagen_portada']; ?>" alt="<?php echo $funcion['titulo']; ?>">
                        <div>
                            <h3><?php echo $funcion['titulo']; ?></h3>
                            <p><strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($funcion['fecha'])); ?></p>
                            <p><strong>Hora:</strong> <?php echo date('H:i', strtotime($funcion['hora'])); ?></p>
                            <p><strong>Sala:</strong> <?php echo $funcion['sala_nombre']; ?></p>
                            <p><strong>Precio unitario:</strong> S/. <?php echo number_format($precio, 2); ?></p>
                        </div>
                    </div>
                </div>

                <div class="formulario-pago">
                    <h2>Completa tus datos</h2>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert error"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label for="cantidad">Cantidad de tickets</label>
                            <select name="cantidad" id="cantidad" required>
                                <?php for($i = 1; $i <= 10; $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
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
                        
                        <div id="tarjeta-info" class="metodo-info">
                            <div class="form-group">
                                <label for="tarjeta-nombre">Nombre en la tarjeta</label>
                                <input type="text" id="tarjeta-nombre" name="tarjeta_nombre">
                            </div>
                            <div class="form-group">
                                <label for="tarjeta-numero">Número de tarjeta</label>
                                <input type="text" id="tarjeta-numero" name="tarjeta_numero" maxlength="16">
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="tarjeta-expiracion">Fecha de expiración</label>
                                    <input type="text" id="tarjeta-expiracion" name="tarjeta_expiracion" placeholder="MM/AA">
                                </div>
                                <div class="form-group">
                                    <label for="tarjeta-cvv">CVV</label>
                                    <input type="text" id="tarjeta-cvv" name="tarjeta_cvv" maxlength="3">
                                </div>
                            </div>
                        </div>
                        
                        <div id="yape-info" class="metodo-info" style="display: none;">
                            <div class="yape-details">
                                <p>Realiza el pago a:</p>
                                <p><strong>Número:</strong> <?php echo YAPE_NUMERO; ?></p>
                                <p><strong>Nombre:</strong> <?php echo YAPE_NOMBRE; ?></p>
                                <img src="assets/qr/yape_qr.png" alt="QR Yape" class="yape-qr">
                                <p>Sube el comprobante en el siguiente campo:</p>
                                <input type="file" name="comprobante">
                            </div>
                        </div>
                        
                        <div class="total">
                            <p>Total a pagar: <span id="total-pago">S/. <?php echo number_format($precio, 2); ?></span></p>
                        </div>
                        
                        <button type="submit" class="btn">Confirmar compra</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="assets/js/pago.js"></script>
</body>
</html>