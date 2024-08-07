<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Perfil</title>
    <link rel="stylesheet" href="Css/vercss.css"> <!-- Vincular el archivo CSS -->
</head>
<body>
    <div class="sidebar">
        <img src="IMG/Logo.jpeg" alt="Logo UTECO">
        <a href="inicio.php">Inicio</a>
        <a href="portafolioadmin.php">Agregar Vacante</a>
        <a href="ver_vacantes.php">Ver Vacantes</a>
        <a href="ver_solicitudes.php">Solicitudes<span class="counter"><?php echo getSolicitudesCount(); ?></span></a>
        <a href="logout.php">Cerrar Sesión</a>
    </div>
    <div class="container">
        <div class="user-info">
            <h1>Perfil del Solicitante</h1>
            <?php
            include('db.php'); // Incluir la conexión a la base de datos

            if (isset($_GET['id'])) {
                $usuario_id = $_GET['id'];
                $sql = "SELECT usuarios.nombre, usuarios.email, perfiles.* FROM usuarios 
                        JOIN perfiles ON usuarios.id = perfiles.usuario_id 
                        WHERE usuarios.id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $usuario_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $imagenPerfil = !empty($row['imagen']) ? 'uploads/' . htmlspecialchars($row['imagen']) : 'IMG/default.JPG';
                    if (!empty($row['imagen']) && !file_exists('uploads/' . htmlspecialchars($row['imagen']))) {
                        $imagenPerfil = 'IMG/default.JPG'; // Ruta de la imagen por defecto
                    }

                    echo "<p><strong>Nombre:</strong> " . htmlspecialchars($row['nombre']) . "</p>";
                    echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
                    echo "<img src='" . htmlspecialchars($imagenPerfil) . "' alt='Foto de Perfil'>";
                    echo "<p><strong>Teléfono:</strong> " . htmlspecialchars($row['telefono']) . "</p>";
                    echo "<p><strong>Dirección:</strong> " . htmlspecialchars($row['direccion']) . "</p>";
                    echo "<p><strong>Experiencia:</strong> " . htmlspecialchars($row['experiencia']) . "</p>";
                    echo "<p><strong>Educación:</strong> " . htmlspecialchars($row['educacion']) . "</p>";
                    echo "<p><strong>Habilidades:</strong> " . htmlspecialchars($row['habilidades']) . "</p>";

                    echo "<a href='enviar_mensaje.php?id=" . htmlspecialchars($usuario_id) . "' class='btn'>Enviar Mensaje</a>";
                } else {
                    echo "<p>Perfil no encontrado.</p>";
                }
            } else {
                echo "<p>ID de usuario no especificado.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>

<?php
function getSolicitudesCount() {
    include('db.php'); // Incluir la conexión a la base de datos
    $sql = "SELECT COUNT(*) as count FROM solicitudes";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}
?>
