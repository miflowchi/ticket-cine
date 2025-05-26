<?php
// Verificar si el usuario está logueado
function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Verificar si el usuario es administrador
function isAdmin() {
    return isLoggedIn() && $_SESSION['admin_level'] >= 1;
}

// Verificar si el usuario es superadmin
function isSuperAdmin() {
    return isLoggedIn() && $_SESSION['admin_level'] >= 2;
}

// Función para iniciar sesión
function login($email, $password, $db) {
    $email = $db->escape($email);
    $query = "SELECT * FROM administradores WHERE email = '$email' LIMIT 1";
    $result = $db->query($query);
    
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        // Comparar contraseña en texto plano
        if ($password === $admin['password']) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_nombre'] = $admin['nombre'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_level'] = $admin['nivel_acceso'];
            return true;
        }
    }
    
    return false;
}

// Función para cerrar sesión
function logout() {
    session_unset();
    session_destroy();
}

// Proteger ruta para administradores
function protectAdminRoute() {
    if (!isAdmin()) {
        header("Location: ../login.php");
        exit();
    }
}
?>