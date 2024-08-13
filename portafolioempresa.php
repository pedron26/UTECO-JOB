<?php
session_start(); // Asegúrate de que esto esté al principio del archivo

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uteco-job";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Asegúrate de que la variable de sesión 'empresa_id' esté definida
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'empresa') {
    header("Location: login.php"); // Redirige si no está logueado o no es una empresa
    exit();
}

// Obtener el ID de la empresa desde la base de datos
$user_id = $_SESSION['user_id'];
$sql_empresa = "SELECT id FROM empresas WHERE usuario_id = '$user_id'";
$result_empresa = $conn->query($sql_empresa);
if ($result_empresa->num_rows > 0) {
    $empresa_row = $result_empresa->fetch_assoc();
    $_SESSION['empresa_id'] = $empresa_row['id'];
} else {
    die("Error: No se encontró la empresa asociada al usuario.");
}

// Subir vacante
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['subir_vacante'])) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha_apertura = $_POST['fecha_apertura'];
    $fecha_cierre = $_POST['fecha_cierre'];
    $imagen = $_FILES['imagen']['name'];
    $empresa_id = $_SESSION['empresa_id']; // Obtener el ID de la empresa desde la sesión

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["imagen"]["name"]);

    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO vacantes (titulo, descripcion, fecha_apertura, fecha_cierre, imagen, empresa_id) 
                VALUES ('$titulo', '$descripcion', '$fecha_apertura', '$fecha_cierre', '$imagen', '$empresa_id')";
        
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
    <title>Portafolio de Empresa - UTECO_JOB</title>
    <link rel="stylesheet" href="Css/portafoliocssadmin.css">
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
        <h1>Gestión de Vacantes</h1>
        
        <div class="form-section">
            <h2>Publicar Vacante</h2>
            <form action="portafolioempresa.php" method="post" enctype="multipart/form-data">
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
            <h2>Vacantes Publicadas</h2>
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
                $empresa_id = $_SESSION['empresa_id']; // Obtener el ID de la empresa desde la sesión
                $sql = "SELECT vacantes.*, 
                        (SELECT COUNT(*) FROM solicitudes WHERE solicitudes.vacante_id = vacantes.id) AS solicitud_count 
                        FROM vacantes WHERE empresa_id = '$empresa_id'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['titulo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fecha_apertura']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fecha_cierre']) . "</td>";
                        echo "<td><img src='uploads/" . htmlspecialchars($row['imagen']) . "' alt='" . htmlspecialchars($row['titulo']) . "'></td>";
                        echo "<td>" . htmlspecialchars($row['solicitud_count']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No hay vacantes publicadas.</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>
