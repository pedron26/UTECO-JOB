<?php
session_start(); // Asegúrate de que esto esté al principio del archivo
include('db.php');

// Verifica si la variable de sesión 'empresa_id' está definida
if (!isset($_SESSION['empresa_id'])) {
    die("Error: No se encontró el ID de la empresa en la sesión.");
}

$empresa_id = $_SESSION['empresa_id'];

// Asegúrate de que la conexión a la base de datos esté correctamente definida
if (!isset($conn)) {
    die("Error en la conexión a la base de datos.");
}

$sql = "SELECT solicitudes.*, usuarios.nombre, usuarios.email, perfiles.imagen, vacantes.titulo AS vacante_titulo 
        FROM solicitudes 
        JOIN usuarios ON solicitudes.usuario_id = usuarios.id 
        JOIN perfiles ON usuarios.id = perfiles.usuario_id 
        JOIN vacantes ON solicitudes.vacante_id = vacantes.id 
        WHERE vacantes.empresa_id = '$empresa_id'";

$result = $conn->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Solicitudes - UTECO_JOB</title>
    <link rel="stylesheet" href="Css/ver_solicitudes.css">
</head>
<body>
    <div class="sidebar">
        <img src="IMG/Logo.jpeg" alt="Logo UTECO">
        <a href="inicio.php">Inicio</a>
        <a href="portafolioempresa.php">Publicar Vacante</a>
        <a href="ver_vacantes_empresa.php">Ver Vacantes</a>
        <a href="ver_solicitudes_empresa.php">Ver Solicitudes</a>
        <a href="logout.php">Cerrar Sesión</a>
    </div>
    <div class="container">
        <h1>Solicitudes Recibidas</h1>
        <table>
            <tr>
                <th>Vacante</th>
                <th>Nombre del Solicitante</th>
                <th>Email</th>
                <th>Imagen</th>
                <th>Fecha de Solicitud</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['vacante_titulo']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td><img src='uploads
                    /" . htmlspecialchars($row['imagen']) . "' alt='" . htmlspecialchars($row['nombre']) . "' width='100'></td>";
                    echo "<td>" . htmlspecialchars($row['fecha_solicitud']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No hay solicitudes recibidas.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
