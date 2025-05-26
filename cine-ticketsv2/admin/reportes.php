<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// Solo administradores
protectAdminRoute();

// Filtros de fechas
$fechaInicio = isset($_GET['inicio']) ? $_GET['inicio'] : '';
$fechaFin = isset($_GET['fin']) ? $_GET['fin'] : '';

$where = '';
if ($fechaInicio && $fechaFin) {
    $where = "WHERE DATE(t.fecha_compra) BETWEEN '$fechaInicio' AND '$fechaFin'";
} elseif ($fechaInicio) {
    $where = "WHERE DATE(t.fecha_compra) >= '$fechaInicio'";
} elseif ($fechaFin) {
    $where = "WHERE DATE(t.fecha_compra) <= '$fechaFin'";
}

$query = "SELECT 
            DATE(t.fecha_compra) as fecha,
            p.titulo,
            COUNT(t.id) as tickets_vendidos,
            SUM(t.precio_total) as total_ventas
          FROM tickets t
          JOIN funciones f ON t.funcion_id = f.id
          JOIN peliculas p ON f.pelicula_id = p.id
          $where
          GROUP BY fecha, p.titulo
          ORDER BY fecha DESC, p.titulo";
$result = $db->query($query);

// Reporte detallado de compras
$detalleQuery = "SELECT 
    t.id,
    t.cliente_nombre,
    t.cliente_email,
    p.titulo,
    t.cantidad,
    t.precio_total,
    t.fecha_compra
  FROM tickets t
  JOIN funciones f ON t.funcion_id = f.id
  JOIN peliculas p ON f.pelicula_id = p.id
  $where
  ORDER BY t.fecha_compra DESC";
$detalleResult = $db->query($detalleQuery);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-container">
    <?php include 'includes/sidebar.php'; ?>
    <main class="admin-main">
        <h1>Reporte de Ventas</h1>
        <form method="GET" class="form-row" style="margin-bottom:20px;">
            <div class="form-group">
                <label for="inicio">Desde</label>
                <input type="date" name="inicio" id="inicio" value="<?php echo $fechaInicio; ?>">
            </div>
            <div class="form-group">
                <label for="fin">Hasta</label>
                <input type="date" name="fin" id="fin" value="<?php echo $fechaFin; ?>">
            </div>
            <div class="form-group" style="align-self: flex-end;">
                <button type="submit" class="btn">Filtrar</button>
            </div>
        </form>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Película</th>
                        <th>Tickets Vendidos</th>
                        <th>Total Ventas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['fecha']; ?></td>
                            <td><?php echo $row['titulo']; ?></td>
                            <td><?php echo $row['tickets_vendidos']; ?></td>
                            <td>S/. <?php echo number_format($row['total_ventas'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Reporte detallado de compras -->
        <h2 style="margin-top:40px;">Detalle de Compras</h2>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID Ticket</th>
                        <th>Cliente</th>
                        <th>Email</th>
                        <th>Película</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($detalle = $detalleResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $detalle['id']; ?></td>
                            <td><?php echo htmlspecialchars($detalle['cliente_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($detalle['cliente_email']); ?></td>
                            <td><?php echo $detalle['titulo']; ?></td>
                            <td><?php echo $detalle['cantidad']; ?></td>
                            <td>S/. <?php echo number_format($detalle['precio_total'], 2); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($detalle['fecha_compra'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
<script src="../assets/js/admin.js"></script>
</body>
</html>
