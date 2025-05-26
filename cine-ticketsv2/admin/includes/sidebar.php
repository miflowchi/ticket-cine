<div class="sidebar">
    <div class="sidebar-header">
        <h2><?php echo SITE_NAME; ?></h2>
        <p><?php echo $_SESSION['admin_nombre']; ?></p>
    </div>
    <nav class="sidebar-nav">
        <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a>
        <a href="peliculas.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'peliculas.php' ? 'active' : ''; ?>">Películas</a>
        <a href="salas.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'salas.php' ? 'active' : ''; ?>">Salas</a>
        <a href="funciones.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'funciones.php' ? 'active' : ''; ?>">Funciones</a>
        <a href="tickets-manuales.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'tickets-manuales.php' ? 'active' : ''; ?>">Tickets Manuales</a>
        <a href="reportes.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'reportes.php' ? 'active' : ''; ?>">Reportes</a>
        <a href="administradores.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'administradores.php' ? 'active' : ''; ?>">Administradores</a>
        <a href="../logout.php?redirect=index" class="btn danger" style="margin: 20px;">Cerrar sesión</a>
    </nav>
</div>
