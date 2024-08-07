<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$vacante_id = $_GET['vacante_id'];

// Verificar si el usuario tiene un perfil completo
$query = "SELECT * FROM perfiles WHERE usuario_id = $user_id";
$result = mysqli_query($conn, $query);
$perfil = mysqli_fetch_assoc($result);

if (!$perfil) {
    header("Location: editar_perfil.php");
    exit();
}

// Verificar si el perfil está completo (este ejemplo asume que el perfil es completo si se tiene una imagen)
if (empty($perfil['imagen'])) {
    header("Location: editar_perfil.php");
    exit();
}

// Procesar la solicitud (aquí deberías implementar la lógica para almacenar la solicitud)
$query = "INSERT INTO solicitudes (usuario_id, vacante_id) VALUES ($user_id, $vacante_id)";
mysqli_query($conn, $query);

header("Location: portafolio.php");
exit();
?>
