<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];

// Obtener información del perfil
$query = "SELECT nombre, imagen FROM perfiles WHERE usuario_id = $user_id";
$result = mysqli_query($conn, $query);
$perfil = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nuevo_nombre = $_POST['nombre'];
    $nueva_clave = $_POST['nueva_clave'];
    $confirmar_clave = $_POST['confirmar_clave'];

    // Validación de contraseñas
    $errors = [];
    if (strlen($nueva_clave) > 14) {
        $errors[] = "La contraseña no puede tener más de 14 caracteres.";
    }
    if ($nueva_clave !== $confirmar_clave) {
        $errors[] = "Las contraseñas no coinciden.";
    }

    if (empty($errors)) {
        if (!empty($nueva_clave)) {
            // Encriptar la nueva contraseña
            $nueva_clave_hash = password_hash($nueva_clave, PASSWORD_BCRYPT);
            $query = "UPDATE perfiles SET nombre = '$nuevo_nombre', clave = '$nueva_clave_hash' WHERE usuario_id = $user_id";
        } else {
            $query = "UPDATE perfiles SET nombre = '$nuevo_nombre' WHERE usuario_id = $user_id";
        }

        if (mysqli_query($conn, $query)) {
            header("Location: perfil.php"); // Redirigir al perfil o a donde quieras
            exit();
        } else {
            $errors[] = "Error al actualizar la información.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración - UTECO JOB</title>
    <link rel="stylesheet" href="Css/configuracion.css">
</head>
<body>
    <header>
        <img src="IMG/Logo.jpeg" alt="Logo UTECO" class="logo">
        <div class="search-bar">
            <input type="text" placeholder="Buscar...">
            <button>Buscar</button>
        </div>
        <div class="user-menu">
            <!-- Mostrar la imagen del perfil si existe -->
            <img src="<?php echo !empty($perfil['imagen']) ? htmlspecialchars($perfil['imagen']) : 'IMG/default.JPG'; ?>" alt="User Icon" class="user-icon" onclick="toggleMenu()">
            <div class="dropdown-menu" id="dropdown-menu">
                <a href="perfil.php">Perfil</a>
                <a href="configurar.php">Configurar</a>
                <a href="logout.php">Cerrar Sesión</a>
            </div>
            <button class="inicio-btn" onclick="window.location.href='portafolio.php'">Inicio</button>
        </div>
    </header>
    <main>
        <h1>Configuración de Cuenta</h1>
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form action="configurar.php" method="POST">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($perfil['nombre'] ?? ''); ?>" required>

            <label for="nueva_clave">Nueva Contraseña</label>
            <input type="password" id="nueva_clave" name="nueva_clave" maxlength="14" placeholder="Máximo 14 caracteres">

            <label for="confirmar_clave">Confirmar Contraseña</label>
            <input type="password" id="confirmar_clave" name="confirmar_clave" maxlength="14" placeholder="Repite la contraseña">

            <button type="submit">Guardar Cambios</button>
        </form>
    </main>

    <script>
        function toggleMenu() {
            var dropdownMenu = document.getElementById('dropdown-menu');
            dropdownMenu.classList.toggle('show');
        }

        window.addEventListener('click', function(event) {
            var dropdownMenu = document.getElementById('dropdown-menu');
            var userIcon = document.querySelector('.user-icon');
            if (!userIcon.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    </script>
</body>
</html>
