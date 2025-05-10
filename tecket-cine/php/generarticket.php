<?php
// Obtener datos del formulario
$movie = $_POST['movie'] ?? 'N/A';
$genre = $_POST['genre'] ?? 'N/A';
$date = $_POST['date'] ?? 'N/A';
$time = $_POST['time'] ?? 'N/A';
$room = $_POST['room'] ?? 'N/A';
$poster = $_POST['poster'] ?? 'default.jpg';

// Generar ticket con diseño
header("Content-type: text/html");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Cine</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .ticket {
            width: 400px;
            background-color: #fff;
            border: 2px dashed #e50914;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .ticket-header {
            background-color: #e50914;
            color: white;
            text-align: center;
            padding: 10px;
        }
        .ticket-body {
            padding: 20px;
            text-align: center;
        }
        .ticket-body img {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .ticket-body h2 {
            margin-bottom: 10px;
        }
        .ticket-body p {
            margin: 5px 0;
        }
        .ticket-footer {
            text-align: center;
            padding: 10px;
            background-color: #f4f4f4;
            font-size: 0.9rem;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="ticket-header">
            <h1>CineTickets 🎟️</h1>
        </div>
        <div class="ticket-body">
            <img src="<?php echo htmlspecialchars($poster); ?>" alt="Póster de <?php echo htmlspecialchars($movie); ?>">
            <h2><?php echo htmlspecialchars($movie); ?></h2>
            <p><strong>Género:</strong> <?php echo htmlspecialchars($genre); ?></p>
            <p><strong>Fecha:</strong> <?php echo htmlspecialchars($date); ?></p>
            <p><strong>Hora:</strong> <?php echo htmlspecialchars($time); ?></p>
            <p><strong>Sala:</strong> <?php echo htmlspecialchars($room); ?></p>
        </div>
        <div class="ticket-footer">
            <p>¡Gracias por su compra!</p>
        </div>
    </div>
</body>
</html>
