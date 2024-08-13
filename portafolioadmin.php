<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uteco-job";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Subir vacante
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['subir_vacante'])) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha_apertura = $_POST['fecha_apertura'];
    $fecha_cierre = $_POST['fecha_cierre'];
    $imagen = $_FILES['imagen']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["imagen"]["name"]);

    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO vacantes (titulo, descripcion, fecha_apertura, fecha_cierre, imagen) VALUES ('$titulo', '$descripcion', '$fecha_apertura', '$fecha_cierre', '$imagen')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Vacante subida exitosamente.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error al subir la imagen.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración del Portafolio - UTECO_JOB</title>
    <link rel="stylesheet" href="Css/portafoliocssadmin.css"> <!-- Vincular el archivo CSS -->
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
        <h1>Administración del Portafolio</h1>
        
        <div class="form-section">
            <h2>Subir Vacante</h2>
            <form action="portafolioadmin.php" method="post" enctype="multipart/form-data">
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="titulo" required><br><br>

                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required></textarea><br><br>

                <label for="fecha_apertura">Fecha de Apertura:</label>
                <input type="date" id="fecha_apertura" name="fecha_apertura" required><br><br>

                <label for="fecha_cierre">Fecha de Cierre:</label>
                <input type="date" id="fecha_cierre" name="fecha_cierre" required><br><br>

                <label for="imagen">Imagen:</label>
                <input type="file" id="imagen" name="imagen" accept="image/*" required><br><br>

                <input type="submit" name="subir_vacante" value="Subir Vacante">
            </form>
        </div>

        <div class="table-section">
            <h2>Vacantes Subidas</h2>
            <table>
                <tr>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Fecha de Apertura</th>
                    <th>Fecha de Cierre</th>
                    <th>Imagen</th>
                    <th>Solicitudes</th>
                </tr>
                <?php
                $sql = "SELECT vacantes.*, (SELECT COUNT(*) FROM solicitudes WHERE solicitudes.vacante_id = vacantes.id) AS solicitud_count FROM vacantes";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['titulo'] . "</td>";
                        echo "<td>" . $row['descripcion'] . "</td>";
                        echo "<td>" . $row['fecha_apertura'] . "</td>";
                        echo "<td>" . $row['fecha_cierre'] . "</td>";
                        echo "<td><img src='uploads/" . $row['imagen'] . "' alt='" . $row['titulo'] . "'></td>";
                        echo "<td>" . $row['solicitud_count'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No hay vacantes disponibles.</td></tr>";
                }
                ?>
            </table>
        </div>

        <div class="table-section">
            <h2>Solicitudes de Vacantes</h2>
            <table>
                <tr>
                    <th>Foto de Perfil</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Fecha de Solicitud</th>
                    <th>Acciones</th>
                </tr>
                <?php
                $sql = "SELECT solicitudes.*, usuarios.nombre, usuarios.email, perfiles.imagen 
                        FROM solicitudes 
                        JOIN usuarios ON solicitudes.usuario_id = usuarios.id 
                        JOIN perfiles ON usuarios.id = perfiles.usuario_id";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><img src='uploads/" . $row['imagen'] . "' alt='" . $row['nombre'] . "' width='50'></td>";
                        echo "<td>" . $row['nombre'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['fecha_solicitud'] . "</td>";
                        echo "<td><a href='ver_perfil.php?id=" . $row['usuario_id'] . "'>Ver Perfil</a> | <a href='enviar_mensaje.php?id=" . $row['usuario_id'] . "'>Enviar Invitación</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay solicitudes.</td></tr>";
                }

                $conn->close();
                ?>
            </table>
        </div>
    </div>
</body>
</html>

<?php
function getSolicitudesCount() {
    global $conn;
    $sql = "SELECT COUNT(*) as count FROM solicitudes";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}

if (isset($_POST['subir_vacante'])) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha_apertura = $_POST['fecha_apertura'];
    $fecha_cierre = $_POST['fecha_cierre'];
    $imagen = $_FILES['imagen']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($imagen);

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file)) {
        $sql = "INSERT INTO vacantes (titulo, descripcion, fecha_apertura, fecha_cierre, imagen) 
                VALUES ('$titulo', '$descripcion', '$fecha_apertura', '$fecha_cierre', '$imagen')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Vacante subida con éxito');</script>";
        } else {
            echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Error al subir la imagen.');</script>";
    }
}
?>
