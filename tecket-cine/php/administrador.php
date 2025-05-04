<?php
session_start();

$servidor = "localhost";
$usuario_db = "root";
$contrasena_db = "";
$base_datos = "venta_ticket_cine";

$conn = new mysqli($servidor, $usuario_db, $contrasena_db, $base_datos);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['email'], $_POST['password'])) {
    $usuario = $_POST['email'];
    $contraseña = $_POST['password'];

    $sql = "SELECT * FROM administrador WHERE nombre = ? AND contrasena = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("ss", $usuario, $contraseña);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $admin = $resultado->fetch_assoc();
    
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id_admin'];
        $_SESSION['admin_usuario'] = $admin['nombre']; // Asegúrate que el campo sea 'nombre'

        echo "Bienvenido. Serás redirigido al panel...";
        echo "<script>setTimeout(function(){ window.location.href = '../admin.php'; }, 2000);</script>";
        exit;
    } else {
        echo "Usuario o contraseña incorrectos.";
    }
}

$conn->close();
?>
