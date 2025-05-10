<?php
// Obtener datos de la película desde la URL
$movie = $_GET['movie'] ?? 'N/A';
$genre = $_GET['genre'] ?? 'N/A';
$date = $_GET['date'] ?? 'N/A';
$time = $_GET['time'] ?? 'N/A';
$room = $_GET['room'] ?? 'N/A';
$poster = $_GET['poster'] ?? 'default.jpg'; // Ruta de la imagen del póster
$description = $_GET['description'] ?? 'Sin descripción disponible.';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra de Ticket</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #141414;
            color: white;
            margin: 0;
            padding: 20px;
        }
        .purchase-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #222;
            padding: 20px;
            border-radius: 8px;
            display: flex;
            gap: 20px;
        }
        .poster {
            flex: 1;
        }
        .poster img {
            width: 100%;
            border-radius: 8px;
        }
        .details {
            flex: 2;
        }
        .details h2 {
            margin-bottom: 10px;
        }
        .details p {
            margin-bottom: 15px;
        }
        .details form {
            margin-top: 20px;
        }
        .details label {
            display: block;
            margin-bottom: 5px;
        }
        .details input, .details select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: white;
        }
        .details button {
            width: 100%;
            padding: 10px;
            background-color: #e50914;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .details button:hover {
            background-color: #ff1b2d;
        }
    </style>
</head>
<body>
    <div class="purchase-container">
        <div class="poster">
            <img src="<?php echo htmlspecialchars($poster); ?>" alt="Póster de <?php echo htmlspecialchars($movie); ?>">
        </div>
        <div class="details">
            <h2><?php echo htmlspecialchars($movie); ?></h2>
            <p><strong>Género:</strong> <?php echo htmlspecialchars($genre); ?></p>
            <p><strong>Fecha:</strong> <?php echo htmlspecialchars($date); ?></p>
            <p><strong>Hora:</strong> <?php echo htmlspecialchars($time); ?></p>
            <p><strong>Sala:</strong> <?php echo htmlspecialchars($room); ?></p>
            <p><strong>Descripción:</strong> <?php echo htmlspecialchars($description); ?></p>

            <form action="php/procesarticket.php" method="POST">
                <input type="hidden" name="movie" value="<?php echo htmlspecialchars($movie); ?>">
                <input type="hidden" name="genre" value="<?php echo htmlspecialchars($genre); ?>">
                <input type="hidden" name="date" value="<?php echo htmlspecialchars($date); ?>">
                <input type="hidden" name="time" value="<?php echo htmlspecialchars($time); ?>">
                <input type="hidden" name="room" value="<?php echo htmlspecialchars($room); ?>">

                <label for="seats">Cantidad de Entradas:</label>
                <input type="number" id="seats" name="seats" min="1" required>

                <label for="payment">Método de Pago:</label>
                <select id="payment" name="payment" required>
                    <option value="Tarjeta">Tarjeta</option>
                    <option value="Yape">Yape</option>
                </select>

                <label for="card-details">Detalles de Tarjeta (si aplica):</label>
                <input type="text" id="card-details" name="card_details" placeholder="Número de tarjeta">

                <button type="submit">Confirmar Compra</button>
            </form>

            <form action="php/generarticket.php" method="POST" style="margin-top: 15px;">
                <input type="hidden" name="movie" value="<?php echo htmlspecialchars($movie); ?>">
                <input type="hidden" name="genre" value="<?php echo htmlspecialchars($genre); ?>">
                <input type="hidden" name="date" value="<?php echo htmlspecialchars($date); ?>">
                <input type="hidden" name="time" value="<?php echo htmlspecialchars($time); ?>">
                <input type="hidden" name="room" value="<?php echo htmlspecialchars($room); ?>">
                <input type="hidden" name="poster" value="<?php echo htmlspecialchars($poster); ?>">
                <button type="submit">Generar/Imprimir Ticket</button>
            </form>
        </div>
    </div>
</body>
</html>
