<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Venta de Tickets</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      background-color: #141414;
      color: white;
    }

    .sidebar {
      width: 220px;
      background-color: #1f1f1f;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      padding-top: 30px;
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
      display: block;
      transition: background 0.3s;
    }

    .sidebar a:hover {
      background-color: #e50914;
    }

    .main {
      margin-left: 220px;
      padding: 30px;
      width: 100%;
    }

    .section-title {
      font-size: 1.8rem;
      margin-bottom: 20px;
    }

    .form-container {
      background-color: #1f1f1f;
      padding: 20px;
      border-radius: 10px;
      max-width: 500px;
    }

    label {
      display: block;
      margin-top: 15px;
      margin-bottom: 5px;
    }

    select, input {
      width: 100%;
      padding: 10px;
      border-radius: 5px;
      border: none;
      background-color: #333;
      color: white;
    }

    .total {
      margin-top: 20px;
      font-size: 1.2rem;
      font-weight: bold;
    }

    button {
      margin-top: 20px;
      padding: 12px;
      width: 100%;
      background-color: #e50914;
      color: white;
      font-size: 1rem;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s;
    }

    button:hover {
      background-color: #ff1b2d;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Admin 🎟️</h2>
    <a href="panel.php">Panel de Películas</a>
    <a href="#">Agregar Administradores</a>
    <a href="tickets-venta.php">Venta de Tickets</a>
    <a href="#">Reportes</a>
    <a href="index.html">Salir</a>
  </div>

  <!-- Main Content -->
  <div class="main">
    <div class="section-title">Venta de Tickets</div>

    <div class="form-container">
      <label for="pelicula">Selecciona una película</label>
      <select id="pelicula">
        <option>Película 1</option>
        <option>Película 2</option>
        <option>Película 3</option>
      </select>

      <label for="fecha">Fecha</label>
      <input type="date" id="fecha">

      <label for="hora">Hora</label>
      <select id="hora">
        <option>12:00</option>
        <option>15:00</option>
        <option>18:00</option>
        <option>21:00</option>
      </select>

      <label for="cantidad">Cantidad de Entradas</label>
      <input type="number" id="cantidad" value="1" min="1" max="10">

      <div class="total">Total: $<span id="total">10.00</span></div>

      <button>Confirmar Venta</button>
    </div>
  </div>

  <!-- JS simple (solo visual para el total) -->
  <script>
    const cantidadInput = document.getElementById('cantidad');
    const totalSpan = document.getElementById('total');
    const precioEntrada = 10;

    cantidadInput.addEventListener('input', () => {
      const cantidad = parseInt(cantidadInput.value) || 1;
      totalSpan.textContent = (cantidad * precioEntrada).toFixed(2);
    });
  </script>

</body>
</html>
