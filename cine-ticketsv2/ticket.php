<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

// Verificar que se haya proporcionado un código de ticket
if (!isset($_GET['codigo'])) {
    header("Location: cartelera.php");
    exit();
}

$codigo = $db->escape($_GET['codigo']);

// Obtener información del ticket
$ticketQuery = "SELECT t.*, p.titulo, p.imagen_portada, f.fecha, f.hora, s.nombre as sala_nombre, s.tipo_sala 
                FROM tickets t 
                JOIN funciones f ON t.funcion_id = f.id 
                JOIN peliculas p ON f.pelicula_id = p.id 
                JOIN salas s ON f.sala_id = s.id 
                WHERE t.codigo = '$codigo'";
$ticketResult = $db->query($ticketQuery);

if ($ticketResult->num_rows === 0) {
    header("Location: cartelera.php");
    exit();
}

$ticket = $ticketResult->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket - <?php echo $ticket['codigo']; ?> | <?php echo SITE_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/ticket.css">
</head>
<body>
    <div class="ticket-container">
        <div class="ticket">
            <header class="ticket-header">
                <h1><?php echo SITE_NAME; ?></h1>
                <p class="ticket-codigo">Ticket #<?php echo $ticket['codigo']; ?></p>
            </header>
            
            <div class="ticket-body">
                <div class="ticket-pelicula">
                    <img src="assets/images/<?php echo $ticket['imagen_portada']; ?>" alt="<?php echo $ticket['titulo']; ?>">
                    <div>
                        <h2><?php echo $ticket['titulo']; ?></h2>
                        <p><strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($ticket['fecha'])); ?></p>
                        <p><strong>Hora:</strong> <?php echo date('H:i', strtotime($ticket['hora'])); ?></p>
                        <p><strong>Sala:</strong> <?php echo $ticket['sala_nombre']; ?> (<?php echo $ticket['tipo_sala']; ?>)</p>
                    </div>
                </div>
                
                <div class="ticket-details">
                    <div class="detail">
                        <span>Cantidad:</span>
                        <span><?php echo $ticket['cantidad']; ?></span>
                    </div>
                    <div class="detail">
                        <span>Precio unitario:</span>
                        <span>S/. <?php echo number_format($ticket['precio_unitario'], 2); ?></span>
                    </div>
                    <div class="detail total">
                        <span>Total:</span>
                        <span>S/. <?php echo number_format($ticket['precio_total'], 2); ?></span>
                    </div>
                    <div class="detail">
                        <span>Método de pago:</span>
                        <span><?php echo ucfirst($ticket['metodo_pago']); ?></span>
                    </div>
                    <div class="detail">
                        <span>Cliente:</span>
                        <span><?php echo $ticket['cliente_nombre']; ?></span>
                    </div>
                </div>
                
                <div class="ticket-qr">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo urlencode(SITE_URL . '/ticket.php?codigo=' . $ticket['codigo']); ?>" alt="QR del ticket">
                    <p>Presentar este código en la entrada</p>
                </div>
            </div>
            
            <footer class="ticket-footer">
                <p>Gracias por su compra. ¡Disfrute de la película!</p>
                <p><?php echo date('d/m/Y H:i', strtotime($ticket['fecha_compra'])); ?></p>
            </footer>
        </div>
        
        <div class="ticket-actions">
            <button onclick="window.print()" class="btn">Imprimir Ticket</button>
            <a href="index.php" class="btn">Volver al inicio</a>
        </div>
    </div>
</body>
</html>