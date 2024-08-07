<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];

// Obtener información del perfil
$query = "SELECT * FROM perfiles WHERE usuario_id = $user_id";
$result = mysqli_query($conn, $query);
$perfil = mysqli_fetch_assoc($result);

// Ruta de imagen predeterminada
$default_image = 'IMG/default.JPG';
$profile_image = !empty($perfil['imagen']) ? $perfil['imagen'] : $default_image;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portafolio - UTECO JOB</title>
    <link rel="stylesheet" href="Css/portafoliocss.css">
</head>
<body>
    <header>
        <img src="IMG/Logo.jpeg" alt="Logo UTECO" class="logo">
        <div class="search-bar">
            <input type="text" placeholder="Buscar...">
            <button>Buscar</button>
        </div>
        <div class="user-menu">
            <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="User Icon" class="user-icon" onclick="toggleMenu()">
            <div class="dropdown-menu" id="dropdown-menu">
                <a href="perfil.php">Perfil</a>
                <a href="configurar.php">Configurar</a>
                <a href="logout.php">Cerrar Sesión</a>
            </div>
            <button class="inicio-btn" onclick="window.location.href='portafolio.php'">Inicio</button>
        </div>
    </header>
    <main>
        <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
        <p>Esta es tu página de portafolio.</p>
        <div class="vacantes">
            <?php
            $query = "SELECT * FROM vacantes WHERE fecha_cierre >= CURDATE() ORDER BY fecha_apertura DESC";
            $result = mysqli_query($conn, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                // Ajusta la ruta de la imagen para la vacante
                $vacante_image = 'uploads/' . htmlspecialchars($row['imagen']);

                echo '<div class="vacante">';
                echo '<img src="' . $vacante_image . '" alt="Vacante">';
                echo '<h2>' . htmlspecialchars($row['titulo']) . '</h2>';
                echo '<p>' . htmlspecialchars($row['descripcion']) . '</p>';
                echo '<p>Fecha de Apertura: ' . htmlspecialchars($row['fecha_apertura']) . '</p>';
                echo '<p>Fecha de Cierre: ' . htmlspecialchars($row['fecha_cierre']) . '</p>';
                echo '<button onclick="solicitarVacante(' . $row['id'] . ')">Solicitar</button>';
                echo '</div>';
            }
            ?>
        </div>
    </main>

    <script>
        function toggleMenu() {
            document.getElementById('dropdown-menu').classList.toggle('show');
        }

        function solicitarVacante(vacanteId) {
            window.location.href = 'solicitar_vacante.php?vacante_id=' + vacanteId;
        }
    </script>
</body>
</html>
