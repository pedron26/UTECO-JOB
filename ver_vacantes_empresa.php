<?php
session_start(); // Asegúrate de que esto esté al principio del archivo
include('db.php');

// Asegúrate de que la variable de sesión 'empresa_id' esté definida
if (!isset($_SESSION['empresa_id'])) {
    die("Error: No se encontró el ID de la empresa en la sesión.");
}

$empresa_id = $_SESSION['empresa_id'];

$sql = "SELECT * FROM vacantes WHERE empresa_id = '$empresa_id'";
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
    <title>Ver Vacantes - UTECO_JOB</title>
    <link rel="stylesheet" href="Css/ver_vacantes.css">
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
        <h1>Vacantes Publicadas</h1>
        <table>
            <tr>
                <th>Título</th>
                <th>Descripción</th>
                <th>Fecha de Apertura</th>
                <th>Fecha de Cierre</th>
                <th>Imagen</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['titulo']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['fecha_apertura']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['fecha_cierre']) . "</td>";
                    echo "<td><img src='uploads/" . htmlspecialchars($row['imagen']) . "' alt='" . htmlspecialchars($row['titulo']) . "' width='100'></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No hay vacantes publicadas.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
