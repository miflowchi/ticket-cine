<?php
// process_purchase.php - Script para procesar la compra de tickets

// Iniciar sesión para manejar el carrito de compras
session_start();

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar los datos de entrada
    $movie_id = filter_input(INPUT_POST, 'movie_id', FILTER_SANITIZE_NUMBER_INT);
    $cinema_id = filter_input(INPUT_POST, 'cinema_id', FILTER_SANITIZE_NUMBER_INT);
    $show_time = filter_input(INPUT_POST, 'show_time', FILTER_SANITIZE_STRING);
    $num_tickets = filter_input(INPUT_POST, 'num_tickets', FILTER_SANITIZE_NUMBER_INT);
    $seat_type = filter_input(INPUT_POST, 'seat_type', FILTER_SANITIZE_STRING);
    
    // Validar que todos los campos requeridos existan
    if (!$movie_id || !$cinema_id || !$show_time || !$num_tickets || !$seat_type) {
        // Redirigir con mensaje de error
        header("Location: error.php?message=Datos+incompletos");
        exit;
    }
    
    // Calcular el precio (esto sería más complejo en una aplicación real)
    $price_per_ticket = 10; // Precio base por ticket
    if ($seat_type === 'VIP') {
        $price_per_ticket += 5; // Incremento por asiento VIP
    }
    $total_price = $price_per_ticket * $num_tickets;

    // Guardar la compra en la sesión (simulación)
    $_SESSION['purchase'] = [
        'movie_id' => $movie_id,
        'cinema_id' => $cinema_id,
        'show_time' => $show_time,
        'num_tickets' => $num_tickets,
        'seat_type' => $seat_type,
        'total_price' => $total_price
    ];

    // Redirigir con mensaje de éxito
    header("Location: success.php?message=Compra+exitosa&total=$total_price");
    exit;
}