<?php
include('db.php'); // Incluir la conexión a la base de datos

function getSolicitudesCount() {
    include('db.php'); 
    $sql = "SELECT COUNT(*) as count FROM solicitudes";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
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
        <a href="control_usuarios.php">Control de Usuarios</a>
        <a href="portafolioadmin.php">Publicar Vacante</a>
        <a href="ver_vacantes.php">Ver Vacantes</a>
        <a href="ver_solicitudes.php">Solicitudes<span class="counter"><?php echo getSolicitudesCount(); ?></span></a>
        <a href="logout.php">Cerrar Sesión</a>
    </div>
    <div class="container">
        <h1>Solicitudes de Vacantes</h1>
        <div class="table-section">
            <h2>Lista de Solicitudes</h2>
            <table>
                <tr>
                    <th>Nombre del Solicitante</th>
                    <th>Email</th>
                    <th>Vacante</th>
                    <th>Fecha de Solicitud</th>
                    <th>Foto de Perfil</th>
                    <th>Acciones</th>
                </tr>
                <?php
                $sql = "SELECT solicitudes.*, usuarios.nombre, usuarios.email, perfiles.imagen, vacantes.titulo AS vacante_titulo 
                        FROM solicitudes 
                        JOIN usuarios ON solicitudes.usuario_id = usuarios.id 
                        JOIN perfiles ON usuarios.id = perfiles.usuario_id 
                        JOIN vacantes ON solicitudes.vacante_id = vacantes.id";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Construir la ruta de la imagen
                        $imagenPerfil = !empty($row['imagen']) ? 'IMG/' . $row['imagen'] : 'IMG/default.JPG';

                        // Comprobar si el archivo realmente existe
                        if (!empty($row['imagen']) && !file_exists('IMG/' . $row['imagen'])) {
                            $imagenPerfil = 'IMG/default.JPG'; // Ruta de la imagen por defecto
                        }

                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['vacante_titulo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fecha_solicitud']) . "</td>";
                        echo "<td><img src='" . htmlspecialchars($imagenPerfil) . "' alt='Foto de Perfil' width='100'></td>";
                        echo "<td>";
                        echo "<a href='ver_perfil.php?id=" . htmlspecialchars($row['usuario_id']) . "'>Ver Perfil</a> | ";
                        echo "<a href='enviar_mensaje.php?id=" . htmlspecialchars($row['usuario_id']) . "'>Enviar Mensaje</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No hay solicitudes.</td></tr>";
                }

                $conn->close();
                ?>
            </table>
        </div>
    </div>
</body>
</html>
