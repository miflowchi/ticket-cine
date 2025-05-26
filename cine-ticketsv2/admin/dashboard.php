<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// Verificar si el usuario es administrador
if (!isAdmin()) {
    header("Location: ../login.php");
    exit();
}

// Obtener estadísticas para el dashboard
$peliculasCount = $db->query("SELECT COUNT(*) as total FROM peliculas")->fetch_assoc()['total'];
$ticketsCount = $db->query("SELECT COUNT(*) as total FROM tickets")->fetch_assoc()['total'];
$ventasHoy = $db->query("SELECT SUM(precio_total) as total FROM tickets WHERE DATE(fecha_compra) = CURDATE()")->fetch_assoc()['total'];
$ventasMes = $db->query("SELECT SUM(precio_total) as total FROM tickets WHERE MONTH(fecha_compra) = MONTH(CURDATE())")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - <?php echo SITE_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="admin-main">
            <h1>Dashboard</h1>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Películas</h3>
                    <p><?php echo $peliculasCount; ?></p>
                </div>
                
                <div class="stat-card">
                    <h3>Tickets Vendidos</h3>
                    <p><?php echo $ticketsCount; ?></p>
                </div>
                
                <div class="stat-card">
                    <h3>Ventas Hoy</h3>
                    <p>S/. <?php echo number_format($ventasHoy ?? 0, 2); ?></p>
                </div>
                
                <div class="stat-card">
                    <h3>Ventas Mes</h3>
                    <p>S/. <?php echo number_format($ventasMes ?? 0, 2); ?></p>
                </div>
            </div>

            <!-- Panel de accesos directos -->
            <div class="admin-panel-shortcuts" style="margin: 40px 0;">
                <h2>Panel de Administración</h2>
                <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                    <a href="peliculas.php" class="btn" style="flex:1; min-width:180px;">Películas</a>
                    <a href="salas.php" class="btn" style="flex:1; min-width:180px;">Salas</a>
                    <a href="funciones.php" class="btn" style="flex:1; min-width:180px;">Funciones</a>
                    <a href="tickets-manuales.php" class="btn" style="flex:1; min-width:180px;">Manual de Usuario</a>
                    <a href="reportes.php" class="btn" style="flex:1; min-width:180px;">Reportes</a>
                    <a href="administradores.php" class="btn" style="flex:1; min-width:180px;">Administradores</a>
                </div>
            </div>
            
            <div class="recent-sales">
                <h2>Ventas Recientes</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Película</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ventasQuery = "SELECT t.id, p.titulo, t.cantidad, t.precio_total, t.fecha_compra 
                                        FROM tickets t 
                                        JOIN funciones f ON t.funcion_id = f.id 
                                        JOIN peliculas p ON f.pelicula_id = p.id 
                                        ORDER BY t.fecha_compra DESC LIMIT 10";
                        $ventasResult = $db->query($ventasQuery);
                        
                        while($venta = $ventasResult->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?php echo $venta['id']; ?></td>
                            <td><?php echo $venta['titulo']; ?></td>
                            <td><?php echo $venta['cantidad']; ?></td>
                            <td>S/. <?php echo number_format($venta['precio_total'], 2); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($venta['fecha_compra'])); ?></td>
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