<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrador - Panel</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      background-color: #141414;
      color: #fff;
    }

    /* Sidebar */
    .sidebar {
      width: 220px;
      background-color:rgb(22, 22, 22);
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      padding-top: 30px;
      display: flex;
      flex-direction: column;
      border-right: 2px solid #e50914;
    }

    .sidebar h2 {
      text-align: center;
      color: #e50914;
      margin-bottom: 30px;
    }

    .sidebar a {
      padding: 15px 20px;
      text-decoration: none;
      color: white;
      transition: background 0.3s;
    }

    .sidebar a:hover {
      background-color: #e50914;
    }

    /* Main Content */
    .main {
      margin-left: 220px;
      padding: 20px;
      width: 100%;
    }

    .section-title {
      font-size: 1.8rem;
      margin-bottom: 20px;
    }

    /* Buttons */
    .buttons {
      display: flex;
      gap: 15px;
      margin-bottom: 20px;
    }

    .buttons button {
      padding: 10px 15px;
      background-color: #e50914;
      border: none;
      border-radius: 5px;
      color: white;
      cursor: pointer;
      transition: background 0.3s;
    }

    .buttons button:hover {
      background-color: #ff1b2d;
    }

    /* Movie Grid */
    .movie-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 10px;
    }

    .movie-card {   
      background-color: #222;
      border-radius: 5px;
      overflow: hidden;
      text-align: center;
      transition: transform 0.3s;
    }

    .movie-card:hover {
      transform: scale(1.05);
    }

    .movie-card img {
      width: 300px; /* Ajustar al ancho del contenedor */
      height: 500px;
      object-fit: cover;
    }

    .movie-card h3 {
      padding: 10px;
      font-size: 1rem;
    }
  </style>
</head>
<body>

  <!-- Sidebar / Menú Lateral -->
  <div class="sidebar">
    <h2>Admin 🎟️</h2>
    <a href="panel.php">Panel de Películas</a>
    <a href="#">Agregar Administradores</a>
    <a href="tickets-venta.php">Venta de Tickets</a>
    <a href="#">Reportes</a>
    <a href="index.html">Salir</a>
  </div>

  <!-- Contenido Principal -->
  <div class="main">
    <div class="section-title">Panel de Películas</div>

    <div class="buttons">
      <button>Agregar Película</button>
      <button>Modificar Película</button>
      <button>Eliminar Película</button>
    </div>

    <div class="movie-grid">
      <div class="movie-card">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS9ozabpBuXpqotfm-U2SRQsi5VRVmdiThYJA&s" alt="Película 1">
        <h3>Película 1</h3>
      </div>
      <div class="movie-card">
        <img src="https://es.web.img3.acsta.net/c_310_420/pictures/22/12/14/17/42/1424940.jpg" alt="Película 2">
        <h3>Película 2</h3>
      </div>
      <div class="movie-card">
        <img src="https://www.lavanguardia.com/peliculas-series/images/movie/poster/2025/2/w300/pVMSRyAiye7gZ8NtuCt1qgbspY9.jpg" alt="Película 3">
        <h3>Película 3</h3>
      </div>
    </div>
  </div>

</body>
</html>
