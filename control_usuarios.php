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

// Activar cuenta de usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['activar'])) {
    $user_id = $_POST['user_id'];
    $sql = "UPDATE usuarios SET estado = 'activo' WHERE id = '$user_id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Cuenta activada exitosamente.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
function getSolicitudesCount() {
    global $conn;
    $sql = "SELECT COUNT(*) as count FROM solicitudes";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}

// Obtener la lista de administradores y usuarios de empresas
$sql_admins = "SELECT u.id, u.nombre, u.email, u.estado, r.nombre AS rol_nombre
               FROM usuarios u
               JOIN roles r ON u.rol_id = r.id
               WHERE r.nombre = 'admin'";
$result_admins = $conn->query($sql_admins);

$sql_empresas = "SELECT u.id, u.nombre, u.email, u.estado, r.nombre AS rol_nombre
                 FROM usuarios u
                 JOIN roles r ON u.rol_id = r.id
                 WHERE u.tipo_cuenta = 'empresa'";
$result_empresas = $conn->query($sql_empresas);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Usuarios - UTECO_JOB</title>
    <link rel="stylesheet" href="Css/control_usuarios.css">
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
        <h1>Control de Usuarios</h1>
        
        <div class="user-section">
            <h2>Administradores</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
                <?php
                if ($result_admins->num_rows > 0) {
                    while ($row = $result_admins->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . ($row['estado'] == 'activo' ? 'Activo' : 'Pendiente') . "</td>";
                        echo "<td>";
                        if ($row['estado'] == 'pendiente') {
                            echo "<form action='control_usuarios.php' method='post'>
                                    <input type='hidden' name='user_id' value='" . htmlspecialchars($row['id']) . "'>
                                    <input type='submit' name='activar' value='Activar'>
                                  </form>";
                        } else {
                            echo "Activado";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay administradores registrados.</td></tr>";
                }
                ?>
            </table>
        </div>

        <div class="user-section">
            <h2>Usuarios de Empresas</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
                <?php
                if ($result_empresas->num_rows > 0) {
                    while ($row = $result_empresas->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . ($row['estado'] == 'activo' ? 'Activo' : 'Pendiente') . "</td>";
                        echo "<td>";
                        if ($row['estado'] == 'pendiente') {
                            echo "<form action='control_usuarios.php' method='post'>
                                    <input type='hidden' name='user_id' value='" . htmlspecialchars($row['id']) . "'>
                                    <input type='submit' name='activar' value='Activar'>
                                  </form>";
                        } else {
                            echo "Activado";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay usuarios de empresas registrados.</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>
