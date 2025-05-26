<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
protectAdminRoute();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Manual de Usuario - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-container">
    <?php include 'includes/sidebar.php'; ?>
    <main class="admin-main">
        <h1>Manual de Usuario: ¿Cómo comprar un ticket?</h1>
        <div class="manual-usuario" style="background:white; padding:30px; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.07);">
            <ol style="font-size:1.1rem; line-height:2;">
                <li>En la página principal, haz clic en <b>Ver Cartelera</b> o navega a la sección <b>Cartelera</b>.</li>
                <li>Busca la película de tu preferencia y haz clic en <b>Comprar Ticket</b>.</li>
                <li>Selecciona la función (fecha, hora y sala) que más te convenga.</li>
                <li>Llena el formulario con tus datos: nombre, correo, cantidad y tipo de ticket.</li>
                <li>Elige el método de pago: <b>Tarjeta</b> o <b>Yape</b>.</li>
                <li>Haz clic en <b>Confirmar compra</b>.</li>
                <li>¡Listo! Verás un mensaje de compra exitosa y podrás descargar o imprimir tu ticket.</li>
                <li>Presenta tu ticket (impreso o digital) en la entrada del cine.</li>
            </ol>
            <p style="margin-top:20px; color:#e50914;"><b>¿Dudas?</b> Contáctanos a nuestro correo o acércate a la boletería del cine.</p>
        </div>
    </main>
</div>
</body>
</html>
