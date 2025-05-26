<?php
require_once 'includes/auth.php';
logout();
$redirect = (isset($_GET['redirect']) && $_GET['redirect'] === 'index') ? 'index.php' : 'login.php';
header("Location: $redirect");
exit();
?>
