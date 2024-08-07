<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $experiencia = $_POST['experiencia'];
    $educacion = $_POST['educacion'];
    $habilidades = $_POST['habilidades'];

    // Manejo de la imagen
    $imagen = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $temp_name = $_FILES['imagen']['tmp_name'];
        $file_name = basename($_FILES['imagen']['name']);
        $upload_dir = 'IMG/';
        $upload_file = $upload_dir . $file_name;

        if (move_uploaded_file($temp_name, $upload_file)) {
            $imagen = $upload_file;
        }
    }

    // Verificar si el perfil ya existe
    $query = "SELECT * FROM perfiles WHERE usuario_id = $user_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Actualizar perfil existente
        if ($imagen) {
            $query = "UPDATE perfiles SET imagen = '$imagen', nombre = '$nombre', email = '$email', telefono = '$telefono', direccion = '$direccion', experiencia = '$experiencia', educacion = '$educacion', habilidades = '$habilidades' WHERE usuario_id = $user_id";
        } else {
            $query = "UPDATE perfiles SET nombre = '$nombre', email = '$email', telefono = '$telefono', direccion = '$direccion', experiencia = '$experiencia', educacion = '$educacion', habilidades = '$habilidades' WHERE usuario_id = $user_id";
        }
    } else {
        // Crear nuevo perfil
        $query = "INSERT INTO perfiles (usuario_id, nombre, email, telefono, direccion, experiencia, educacion, habilidades, imagen) VALUES ($user_id, '$nombre', '$email', '$telefono', '$direccion', '$experiencia', '$educacion', '$habilidades', '$imagen')";
    }

    if (mysqli_query($conn, $query)) {
        header("Location: perfil.php"); // Redirigir para evitar el reenvío del formulario
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Obtener información del perfil
$query = "SELECT * FROM perfiles WHERE usuario_id = $user_id";
$result = mysqli_query($conn, $query);
$perfil = mysqli_fetch_assoc($result);

// Establecer una imagen por defecto si no hay una imagen cargada
$imagenPerfil = !empty($perfil['imagen']) ? $perfil['imagen'] : 'IMG/default.JPG';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear o Actualizar Perfil - UTECO JOB</title>
    <link rel="stylesheet" href="Css/perfil.css">
</head>
<body>
    <header>
        <img src="IMG/Logo.jpeg" alt="Logo UTECO" class="logo">
        <div class="search-bar">
            <input type="text" placeholder="Buscar...">
            <button>Buscar</button>
        </div>
        <div class="user-menu">
            <img src="<?php echo htmlspecialchars($imagenPerfil); ?>" alt="User Icon" class="user-icon" onclick="toggleMenu()">
            <div class="dropdown-menu" id="dropdown-menu">
                <a href="perfil.php">Perfil</a>
                <a href="configurar.php">Configurar</a>
                <a href="logout.php">Cerrar Sesión</a>
            </div>
            <button class="inicio-btn" onclick="window.location.href='portafolio.php'">Inicio</button>
        </div>
    </header>
    <main>
        <h1>Crear o Actualizar Perfil</h1>
        <form action="perfil.php" method="POST" enctype="multipart/form-data">
            <!-- Imagen de Perfil -->
            <div class="profile-img-container">
                <img id="profile-img-preview" src="<?php echo htmlspecialchars($imagenPerfil); ?>" alt="Imagen de Perfil" class="profile-img">
                <label for="imagen" class="upload-btn">Subir Imagen</label>
                <input type="file" id="imagen" name="imagen" style="display:none;" onchange="previewImage(event)">
            </div>

            <!-- Campos del Formulario -->
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($perfil['nombre'] ?? ''); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($perfil['email'] ?? ''); ?>" required>

            <label for="telefono">Teléfono</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($perfil['telefono'] ?? ''); ?>" required>

            <label for="direccion">Dirección</label>
            <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($perfil['direccion'] ?? ''); ?>" required>

            <label for="experiencia">Experiencia</label>
            <textarea id="experiencia" name="experiencia" rows="4" required><?php echo htmlspecialchars($perfil['experiencia'] ?? ''); ?></textarea>

            <label for="educacion">Educación</label>
            <textarea id="educacion" name="educacion" rows="4" required><?php echo htmlspecialchars($perfil['educacion'] ?? ''); ?></textarea>

            <label for="habilidades">Habilidades</label>
            <textarea id="habilidades" name="habilidades" rows="4" required><?php echo htmlspecialchars($perfil['habilidades'] ?? ''); ?></textarea>

            <button type="submit">Guardar</button>
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

        function previewImage(event) {
            var input = event.target;
            var file = input.files[0];
            var reader = new FileReader();
            
            reader.onload = function(e) {
                var preview = document.getElementById('profile-img-preview');
                preview.src = e.target.result;
            }
            
            reader.readAsDataURL(file);
        }
    </script>
</body>
</html>
