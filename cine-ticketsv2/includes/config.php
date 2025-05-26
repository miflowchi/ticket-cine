<?php
// Configuración básica
define('SITE_NAME', 'CineTicketPro');
define('SITE_URL', 'http://localhost/cine-tickets');
define('DEFAULT_TIMEZONE', 'America/Lima');

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cine_tickets');

// Configuración de pagos
define('YAPE_NUMERO', '999888777');
define('YAPE_NOMBRE', 'CineTicketPro SAC');
define('COMISION', 0.05); // 5% de comisión

// Iniciar sesión
session_start();

// Otras configuraciones
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>