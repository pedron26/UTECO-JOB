<?php
session_start();

// Destruir la sesión actual y eliminar todos los datos de sesión
session_unset();
session_destroy();

// Redirigir al usuario a la página de inicio de sesión o a la página principal
header("Location: login.php");
exit();
?>
