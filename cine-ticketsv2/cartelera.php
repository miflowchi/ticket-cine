<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

// Obtener todas las películas agrupadas por género
$peliculasPorGenero = [];
$generosQuery = "SELECT DISTINCT genero FROM peliculas WHERE estado = 'cartelera'";
$generosResult = $db->query($generosQuery);

while($genero = $generosResult->fetch_assoc()) {
    $peliculasQuery = "SELECT * FROM peliculas WHERE genero = '{$genero['genero']}' AND estado = 'cartelera'";
    $peliculasResult = $db->query($peliculasQuery);
    
    $peliculasPorGenero[$genero['genero']] = [];
    while($pelicula = $peliculasResult->fetch_assoc()) {
        $peliculasPorGenero[$genero['genero']][] = $pelicula;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartelera - <?php echo SITE_NAME; ?></title>
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
                <a href="cartelera.php" class="active">Cartelera</a>
                <a href="login.php">Admin</a>
            </nav>
        </div>
    </header>

    <main class="main">
        <section class="cartelera">
            <div class="container">
                <h2>Cartelera</h2>
                
                <?php foreach($peliculasPorGenero as $genero => $peliculas): ?>
                <div class="genero-section">
                    <h3><?php echo $genero; ?></h3>
                    <div class="peliculas-grid">
                        <?php foreach($peliculas as $pelicula): ?>
                        <div class="pelicula-card">
                            <img src="assets/images/<?php echo $pelicula['imagen_portada']; ?>" alt="<?php echo $pelicula['titulo']; ?>">
                            <h4><?php echo $pelicula['titulo']; ?></h4>
                            <p><?php echo $pelicula['clasificacion']; ?> | <?php echo $pelicula['duracion']; ?> min</p>
                            <a href="compra.php?pelicula=<?php echo $pelicula['id']; ?>" class="btn">Comprar Ticket</a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
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