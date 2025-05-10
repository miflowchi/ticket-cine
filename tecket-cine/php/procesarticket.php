<?php
session_start();

// Obtener datos del formulario
$movie = $_POST['movie'] ?? 'N/A';
$genre = $_POST['genre'] ?? 'N/A';
$date = $_POST['date'] ?? 'N/A';
$time = $_POST['time'] ?? 'N/A';
$room = $_POST['room'] ?? 'N/A';
$seats = $_POST['seats'] ?? 0;
$payment = $_POST['payment'] ?? 'N/A';
$card_details = $_POST['card_details'] ?? 'N/A';

// Simular procesamiento de compra
$_SESSION['ticket'] = [
    'movie' => $movie,
    'genre' => $genre,
    'date' => $date,
    'time' => $time,
    'room' => $room,
    'seats' => $seats,
    'payment' => $payment,
    'card_details' => $card_details
];

// Redirigir a la página de éxito
header("Location: ../compraticket.php?success=1");
exit;
?>
