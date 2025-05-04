<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Películas</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #141414;
      color: #fff;
    }

    header {
      background-color: #1f1f1f;
      padding: 20px;
      text-align: center;
      font-size: 1.5rem;
      border-bottom: 2px solid #e50914;
    }

    .buttons {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin: 20px;
    }

    .buttons button {
      padding: 10px 20px;
      border: none;
      background-color: #e50914;
      color: #fff;
      font-size: 1rem;
      cursor: pointer;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    .buttons button:hover {
      background-color: #ff1b2d;
    }

    .movie-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 20px;
      padding: 20px;
    }

    .movie-card {
      background-color: #222;
      border-radius: 10px;
      overflow: hidden;
      text-align: center;
      transition: transform 0.3s;
    }

    .movie-card:hover {
      transform: scale(1.05);
    }

    .movie-card img {
      width: 100%; /* Ajustar al ancho del contenedor */
      height: 270px; /* Altura fija para todas las imágenes */
      object-fit: cover; /* Mantener proporciones y recortar si es necesario */
    }

    .movie-card h3 {
      padding: 10px;
      font-size: 1rem;
    }
  </style>
</head>
<body>

  <header>
    🎬 Panel de Películas
  </header>

  <div class="buttons">
    <button>Agregar Película</button>
    <button>Modificar Película</button>
    <button>Eliminar Película<button>
  </div>

  <div class="movie-grid">
    <div class="movie-card">
      <img src="https://hips.hearstapps.com/hmg-prod/images/punales-por-la-espalda-el-misterio-de-glass-onion-66adf08de1be1.jpg?crop=1xw:1xh;center,top&resize=980:*" alt="Película 1">
      <h3>Película 1</h3>
    </div>
    <div class="movie-card">
      <img src="https://cdn.hobbyconsolas.com/sites/navi.axelspringer.es/public/media/image/2018/05/mowgli.jpg?tf=3840x" alt="Película 2">
      <h3>Película 2</h3>
    </div>
    <div class="movie-card">
      <img src="https://es.web.img2.acsta.net/c_310_420/img/6d/7e/6d7ebaf55a56605c074747f74824fb99.jpg" alt="Película 3">
      <h3>Película 3</h3>
    </div>

  </div>

</body>
</html>
