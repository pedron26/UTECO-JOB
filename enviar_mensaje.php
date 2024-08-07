<?php
// Incluir la conexión a la base de datos
include('db.php');

// Definir la función getSolicitudesCount
function getSolicitudesCount() {
    global $conn; // Usar la conexión global a la base de datos
    if ($conn) {
        $sql = "SELECT COUNT(*) as count FROM solicitudes";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['count'];
    } else {
        return 0; // Retornar 0 si la conexión no está disponible
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar Mensaje - UTECO_JOB</title>
    <link rel="stylesheet" href="Css/mensajes.css"> <!-- Vincular el archivo CSS -->
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
        <h1>Enviar Mensaje</h1>
        <?php
        if (isset($_GET['id'])) {
            $usuario_id = $_GET['id'];
            $sql = "SELECT email FROM usuarios WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $usuario_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $email = $row['email'];
            } else {
                echo "<p>Usuario no encontrado.</p>";
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $asunto = $_POST['asunto'];
                $mensaje = $_POST['mensaje'];
                
                // Asunto y cuerpo del correo
                $cuerpo = "<p>De: $email</p><p>Mensaje: " . nl2br(htmlspecialchars($mensaje)) . "</p>";
                
                // Encabezados del correo
                $headers = "From: tu_correo@example.com\r\n";
                $headers .= "Reply-To: tu_correo@example.com\r\n";
                $headers .= "Content-type: text/html\r\n";

                // Enviar el correo
                if (mail($email, $asunto, $cuerpo, $headers)) {
                    echo "<p>Mensaje enviado a $email.</p>";
                } else {
                    echo "<p>No se pudo enviar el mensaje.</p>";
                }
            }
        } else {
            echo "<p>ID de usuario no especificado.</p>";
        }

        $conn->close();
        ?>
        <form method="post" action="">
            <label for="asunto">Asunto:</label>
            <input type="text" id="asunto" name="asunto" required>

            <label for="mensaje">Mensaje:</label>
            <textarea id="mensaje" name="mensaje" rows="5" required></textarea>

            <button type="submit">Enviar Mensaje</button>
        </form>
    </div>
</body>
</html>
