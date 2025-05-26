<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

// Verificar si el usuario es superadmin
if (!isSuperAdmin()) {
    header("Location: ../login.php");
    exit();
}

// Procesar formulario de agregar/editar administrador
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $nombre = $db->escape($_POST['nombre']);
    $email = $db->escape($_POST['email']);
    $nivel_acceso = (int)$_POST['nivel_acceso'];
    
    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Por favor ingrese un email válido.";
    } else {
        // Si es nuevo administrador o se cambió el email
        if ($id === 0 || ($id > 0 && $_POST['email_original'] !== $email)) {
            // Verificar si el email ya existe
            $checkQuery = "SELECT id FROM administradores WHERE email = '$email'";
            if ($id > 0) {
                $checkQuery .= " AND id != $id";
            }
            
            if ($db->query($checkQuery)->num_rows > 0) {
                $error = "El email ya está registrado.";
            }
        }
        
        if (!isset($error)) {
            // Si es nuevo registro o se cambió la contraseña
            if ($id === 0 || !empty($_POST['password'])) {
                $password = $db->escape($_POST['password']); // Guardar en texto plano
                $passwordField = ", password = '$password'";
            } else {
                $passwordField = "";
            }
            
            if ($id > 0) {
                // Actualizar administrador existente
                $query = "UPDATE administradores SET 
                          nombre = '$nombre', 
                          email = '$email', 
                          nivel_acceso = $nivel_acceso 
                          $passwordField 
                          WHERE id = $id";
            } else {
                // Insertar nuevo administrador
                $query = "INSERT INTO administradores (nombre, email, password, nivel_acceso) 
                          VALUES ('$nombre', '$email', '$password', $nivel_acceso)";
            }
            
            if ($db->query($query)) {
                $success = "Administrador " . ($id > 0 ? "actualizado" : "agregado") . " correctamente.";
            } else {
                $error = "Error al " . ($id > 0 ? "actualizar" : "agregar") . " el administrador.";
            }
        }
    }
}

// Procesar eliminación de administrador
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // No permitir eliminarse a sí mismo
    if ($id !== $_SESSION['admin_id']) {
        $deleteQuery = "DELETE FROM administradores WHERE id = $id";
        
        if ($db->query($deleteQuery)) {
            $success = "Administrador eliminado correctamente.";
        } else {
            $error = "Error al eliminar el administrador.";
        }
    } else {
        $error = "No puedes eliminar tu propia cuenta.";
    }
}

// Obtener administrador para edición
$adminEdit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $query = "SELECT * FROM administradores WHERE id = $id";
    $result = $db->query($query);
    $adminEdit = $result->fetch_assoc();
}

// Obtener todos los administradores
$adminsQuery = "SELECT * FROM administradores ORDER BY nombre";
$adminsResult = $db->query($adminsQuery);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Administradores - <?php echo SITE_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="admin-main">
            <h1><?php echo isset($adminEdit) ? 'Editar Administrador' : 'Agregar Administrador'; ?></h1>
            
            <?php if (isset($success)): ?>
                <div class="alert success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" class="form-admin">
                <?php if (isset($adminEdit)): ?>
                    <input type="hidden" name="id" value="<?php echo $adminEdit['id']; ?>">
                    <input type="hidden" name="email_original" value="<?php echo $adminEdit['email']; ?>">
                <?php endif; ?>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre completo</label>
                        <input type="text" name="nombre" id="nombre" required 
                               value="<?php echo isset($adminEdit) ? $adminEdit['nombre'] : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <input type="email" name="email" id="email" required 
                               value="<?php echo isset($adminEdit) ? $adminEdit['email'] : ''; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="password"><?php echo isset($adminEdit) ? 'Nueva contraseña' : 'Contraseña'; ?></label>
                        <input type="password" name="password" id="password" <?php echo !isset($adminEdit) ? 'required' : ''; ?>>
                        <?php if (isset($adminEdit)): ?>
                            <small>Dejar en blanco para mantener la contraseña actual</small>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="nivel_acceso">Nivel de acceso</label>
                        <select name="nivel_acceso" id="nivel_acceso" required>
                            <option value="1" <?php echo (isset($adminEdit) && $adminEdit['nivel_acceso'] == 1) ? 'selected' : ''; ?>>Administrador</option>
                            <option value="2" <?php echo (isset($adminEdit) && $adminEdit['nivel_acceso'] == 2) ? 'selected' : ''; ?>>Super Administrador</option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="btn"><?php echo isset($adminEdit) ? 'Actualizar' : 'Agregar'; ?> Administrador</button>
                
                <?php if (isset($adminEdit)): ?>
                    <a href="administradores.php" class="btn cancelar">Cancelar</a>
                <?php endif; ?>
            </form>
            
            <h2>Lista de Administradores</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Nivel</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($admin = $adminsResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $admin['id']; ?></td>
                            <td><?php echo $admin['nombre']; ?></td>
                            <td><?php echo $admin['email']; ?></td>
                            <td>
                                <?php if ($admin['nivel_acceso'] == 2): ?>
                                    <span class="badge superadmin">Super Admin</span>
                                <?php else: ?>
                                    <span class="badge admin">Admin</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($admin['fecha_creacion'])); ?></td>
                            <td>
                                <a href="administradores.php?edit=<?php echo $admin['id']; ?>" class="btn small">Editar</a>
                                <?php if ($admin['id'] != $_SESSION['admin_id']): ?>
                                    <a href="administradores.php?delete=<?php echo $admin['id']; ?>" class="btn small danger" onclick="return confirm('¿Estás seguro de eliminar este administrador?');">Eliminar</a>
                                <?php else: ?>
                                    <span class="btn small disabled">Tu cuenta</span>
                                <?php endif; ?>
                            </td>
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