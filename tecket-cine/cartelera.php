<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartelera</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #141414;
            color: white;
            margin: 0;
            padding: 0;
        }
        .movie-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .movie-card {
            background-color: #222;
            border-radius: 8px;
            overflow: hidden;
            text-align: center;
            padding: 15px;
        }
        .movie-card img {
            width: 200px;
            height: 250px;
            object-fit: cover;
            border-radius: 8px;
        }
        .movie-card h3 {
            margin: 10px 0;
        }
        .movie-card p {
            margin: 5px 0;
        }
        .movie-card button {
            background-color: #e50914;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .movie-card button:hover {
            background-color: #ff1b2d;
        }
        #purchase-form {
            display: none;
            background-color: #222;
            padding: 20px;
            border-radius: 8px;
            margin: 20px auto;
            max-width: 500px;
        }
        #purchase-form label {
            display: block;
            margin-bottom: 5px;
        }
        #purchase-form input, #purchase-form select, #purchase-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: white;
        }
        #purchase-form button {
            background-color: #e50914;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        #purchase-form button:hover {
            background-color: #ff1b2d;
        }
    </style>
</head>
<body>
    <header>
        <h1 style="text-align: center; padding: 20px; background-color: #1f1f1f; border-bottom: 2px solid #e50914;">Cartelera</h1>
    </header>

    <div class="movie-list">
        <div class="movie-card">
            <img src="https://es.web.img2.acsta.net/c_310_420/img/6d/7e/6d7ebaf55a56605c074747f74824fb99.jpg" alt="Película 1">
            <h3>Película 1</h3>
            <p>Género: Acción</p>
            <p>Fecha: 2023-10-15</p>
            <p>Hora: 18:00</p>
            <p>Sala: 1</p>
            <button onclick="redirectToPurchase('Película 1', 'Acción', '2023-10-15', '18:00', 'Sala 1')">Comprar Ticket</button>
        </div>
        <div class="movie-card">
            <img src="https://hips.hearstapps.com/hmg-prod/images/wheelman-1584377342.jpg?crop=1xw:1xh;center,top&resize=980:*" alt="Película 2">
            <h3>Película 2</h3>
            <p>Género: Ciencia Ficción</p>
            <p>Fecha: 2023-10-16</p>
            <p>Hora: 20:00</p>
            <p>Sala: 2</p>
            <button onclick="redirectToPurchase('Película 2', 'Ciencia Ficción', '2023-10-16', '20:00', 'Sala 2')">Comprar Ticket</button>
        </div>
         <div class="movie-card">
            <img src="https://hips.hearstapps.com/hmg-prod/images/wheelman-1584377342.jpg?crop=1xw:1xh;center,top&resize=980:*" alt="Película 2">
            <h3>Película 2</h3>
            <p>Género: Ciencia Ficción</p>
            <p>Fecha: 2023-10-16</p>
            <p>Hora: 20:00</p>
            <p>Sala: 2</p>
            <button onclick="redirectToPurchase('Película 2', 'Ciencia Ficción', '2023-10-16', '20:00', 'Sala 2')">Comprar Ticket</button>
        </div>
         <div class="movie-card">
            <img src="https://hips.hearstapps.com/hmg-prod/images/wheelman-1584377342.jpg?crop=1xw:1xh;center,top&resize=980:*" alt="Película 2">
            <h3>Película 2</h3>
            <p>Género: Ciencia Ficción</p>
            <p>Fecha: 2023-10-16</p>
            <p>Hora: 20:00</p>
            <p>Sala: 2</p>
            <button onclick="redirectToPurchase('Película 2', 'Ciencia Ficción', '2023-10-16', '20:00', 'Sala 2')">Comprar Ticket</button>
        </div>
         <div class="movie-card">
            <img src="https://hips.hearstapps.com/hmg-prod/images/wheelman-1584377342.jpg?crop=1xw:1xh;center,top&resize=980:*" alt="Película 2">
            <h3>Película 2</h3>
            <p>Género: Ciencia Ficción</p>
            <p>Fecha: 2023-10-16</p>
            <p>Hora: 20:00</p>
            <p>Sala: 2</p>
            <button onclick="redirectToPurchase('Película 2', 'Ciencia Ficción', '2023-10-16', '20:00', 'Sala 2')">Comprar Ticket</button>
        </div>
         <div class="movie-card">
            <img src="https://hips.hearstapps.com/hmg-prod/images/wheelman-1584377342.jpg?crop=1xw:1xh;center,top&resize=980:*" alt="Película 2">
            <h3>Película 2</h3>
            <p>Género: Ciencia Ficción</p>
            <p>Fecha: 2023-10-16</p>
            <p>Hora: 20:00</p>
            <p>Sala: 2</p>
            <button onclick="redirectToPurchase('Película 2', 'Ciencia Ficción', '2023-10-16', '20:00', 'Sala 2')">Comprar Ticket</button>
        </div>
         <div class="movie-card">
            <img src="https://hips.hearstapps.com/hmg-prod/images/wheelman-1584377342.jpg?crop=1xw:1xh;center,top&resize=980:*" alt="Película 2">
            <h3>Película 2</h3>
            <p>Género: Ciencia Ficción</p>
            <p>Fecha: 2023-10-16</p>
            <p>Hora: 20:00</p>
            <p>Sala: 2</p>
            <button onclick="redirectToPurchase('Película 2', 'Ciencia Ficción', '2023-10-16', '20:00', 'Sala 2')">Comprar Ticket</button>
        </div>
        <!-- Add more movies as needed -->
    </div>

    <div id="purchase-form">
        <h2>Formulario de Compra</h2>
        <form action="php/compraticket.php" method="POST">
            <label for="movie">Película:</label>
            <input type="text" id="movie" name="movie" readonly>
            
            <label for="genre">Género:</label>
            <input type="text" id="genre" name="genre" readonly>
            
            <label for="date">Fecha:</label>
            <input type="text" id="date" name="date" readonly>
            
            <label for="time">Hora:</label>
            <input type="text" id="time" name="time" readonly>
            
            <label for="room">Sala:</label>
            <input type="text" id="room" name="room" readonly>
            
            <label for="seats">Cantidad de Entradas:</label>
            <input type="number" id="seats" name="seats" min="1" required>
            
            <label for="payment">Método de Pago:</label>
            <select id="payment" name="payment" required>
                <option value="Tarjeta">Tarjeta</option>
                <option value="Efectivo">Efectivo</option>
            </select>
            
            <button type="submit">Confirmar Compra</button>
        </form>
        <button onclick="closePurchaseForm()">Cancelar</button>
    </div>

    <script>
        function redirectToPurchase(movie, genre, date, time, room) {
            const url = `compraticket.php?movie=${encodeURIComponent(movie)}&genre=${encodeURIComponent(genre)}&date=${encodeURIComponent(date)}&time=${encodeURIComponent(time)}&room=${encodeURIComponent(room)}`;
            window.location.href = url;
        }

        function openPurchaseForm(movie, genre, date, time, room) {
            document.getElementById('movie').value = movie;
            document.getElementById('genre').value = genre;
            document.getElementById('date').value = date;
            document.getElementById('time').value = time;
            document.getElementById('room').value = room;
            document.getElementById('purchase-form').style.display = 'block';
        }

        function closePurchaseForm() {
            document.getElementById('purchase-form').style.display = 'none';
        }
    </script>
</body>
</html>
