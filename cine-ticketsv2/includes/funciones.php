<?php
// Generar código único para tickets
function generarCodigoTicket() {
    return strtoupper(uniqid('TICKET'));
}

// Formatear fecha en español
function formatFecha($fecha, $formato = 'd/m/Y H:i') {
    $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    
    $timestamp = strtotime($fecha);
    $diaSemana = $dias[date('w', $timestamp)];
    $dia = date('d', $timestamp);
    $mes = $meses[date('n', $timestamp) - 1];
    $anio = date('Y', $timestamp);
    $hora = date('H:i', $timestamp);
    
    return "$diaSemana, $dia de $mes de $anio - $hora";
}

// Subir imagen y devolver el nombre del archivo
function subirImagen($file, $directorio = '../assets/images/') {
    if ($file['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $nombreArchivo = uniqid() . '.' . $ext;
        $rutaCompleta = $directorio . $nombreArchivo;
        
        if (move_uploaded_file($file['tmp_name'], $rutaCompleta)) {
            return $nombreArchivo;
        }
    }
    
    return null;
}

// Generar reporte de ventas
function generarReporteVentas($db, $fechaInicio = null, $fechaFin = null) {
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
                COUNT(t.id) as cantidad_tickets,
                SUM(t.precio_total) as total_ventas,
                p.titulo as pelicula_mas_vendida
              FROM tickets t
              JOIN funciones f ON t.funcion_id = f.id
              JOIN peliculas p ON f.pelicula_id = p.id
              $where
              GROUP BY DATE(t.fecha_compra)
              ORDER BY fecha DESC";
    
    return $db->query($query);
}

// Obtener estadísticas rápidas
function obtenerEstadisticas($db) {
    $stats = [];
    
    // Total películas
    $query = "SELECT COUNT(*) as total FROM peliculas";
    $result = $db->query($query);
    $stats['peliculas'] = $result->fetch_assoc()['total'];
    
    // Total tickets vendidos
    $query = "SELECT COUNT(*) as total FROM tickets";
    $result = $db->query($query);
    $stats['tickets'] = $result->fetch_assoc()['total'];
    
    // Ventas hoy
    $query = "SELECT SUM(precio_total) as total FROM tickets WHERE DATE(fecha_compra) = CURDATE()";
    $result = $db->query($query);
    $stats['ventas_hoy'] = $result->fetch_assoc()['total'] ?? 0;
    
    // Ventas este mes
    $query = "SELECT SUM(precio_total) as total FROM tickets WHERE MONTH(fecha_compra) = MONTH(CURDATE()) AND YEAR(fecha_compra) = YEAR(CURDATE())";
    $result = $db->query($query);
    $stats['ventas_mes'] = $result->fetch_assoc()['total'] ?? 0;
    
    // Película más vendida
    $query = "SELECT p.titulo, COUNT(t.id) as ventas 
              FROM tickets t
              JOIN funciones f ON t.funcion_id = f.id
              JOIN peliculas p ON f.pelicula_id = p.id
              GROUP BY p.id
              ORDER BY ventas DESC
              LIMIT 1";
    $result = $db->query($query);
    $stats['pelicula_popular'] = $result->fetch_assoc();
    
    return $stats;
}
?>