<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

// Obtener películas en cartelera
$peliculasQuery = "SELECT * FROM peliculas WHERE estado = 'cartelera' ORDER BY fecha_estreno DESC LIMIT 5";
$peliculasResult = $db->query($peliculasQuery);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Inicio</title>
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
        <section class="hero">
            <div class="container">
                <h2>Disfruta del mejor cine</h2>
                <p>Compra tus tickets online y evita las colas</p>
                <a href="cartelera.php" class="btn">Ver Cartelera</a>
            </div>
        </section>

        <section class="destacados">
            <div class="container">
                <h2>Películas en cartelera</h2>
                <div class="peliculas-grid">
                    <?php while($pelicula = $peliculasResult->fetch_assoc()): ?>
                    <div class="pelicula-card">
                        <img src="assets/images/<?php echo $pelicula['imagen_portada']; ?>" alt="<?php echo $pelicula['titulo']; ?>">
                        <h3><?php echo $pelicula['titulo']; ?></h3>
                        <p><?php echo $pelicula['genero']; ?> | <?php echo $pelicula['clasificacion']; ?></p>
                        <a href="compra.php?pelicula=<?php echo $pelicula['id']; ?>" class="btn">Comprar Ticket</a>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>

        <!-- Manual de usuario para clientes -->
        <section class="manual-usuario" style="margin: 50px 0;">
            <div class="container">
                <h2>¿Cómo comprar tu ticket online?</h2>
                <ol style="font-size:1.1rem; line-height:2;">
                    <li>Haz clic en <b>Ver Cartelera</b> o navega a la sección <b>Cartelera</b>.</li>
                    <li>Elige la película que deseas ver y haz clic en <b>Comprar Ticket</b>.</li>
                    <li>Selecciona la función (fecha, hora y sala) que prefieras.</li>
                    <li>Completa el formulario con tus datos y elige el método de pago.</li>
                    <li>Haz clic en <b>Confirmar compra</b> y descarga o imprime tu ticket.</li>
                    <li>Presenta tu ticket en la entrada del cine. ¡Disfruta la función!</li>
                </ol>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>