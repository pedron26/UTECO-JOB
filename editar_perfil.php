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

// Manejo del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $experiencia = $_POST['experiencia'];
    $educacion = $_POST['educacion'];
    $habilidades = $_POST['habilidades'];
    $imagen = $_FILES['imagen']['name'];
    
    if ($imagen) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($imagen);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file);
        $imagen = $target_file;
    } else {
        $imagen = $perfil['imagen']; // Mantener la imagen existente si no se carga una nueva
    }

    $query = "UPDATE perfiles SET nombre='$nombre', email='$email', telefono='$telefono', direccion='$direccion', experiencia='$experiencia', educacion='$educacion', habilidades='$habilidades', imagen='$imagen' WHERE usuario_id=$user_id";
    mysqli_query($conn, $query);

    header("Location: portafolio.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - UTECO JOB</title>
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
            <img src="IMG/default.JPG" alt="User Icon" class="user-icon" onclick="toggleMenu()">
            <div class="dropdown-menu" id="dropdown-menu">
                <a href="perfil.php">Perfil</a>
                <a href="configurar.php">Configurar</a>
                <a href="logout.php">Cerrar Sesión</a>
            </div>
        </div>
    </header>
    <main>
        <h1>Editar Perfil</h1>
        <form action="editar_perfil.php" method="POST" enctype="multipart/form-data">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($perfil['nombre']); ?>" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($perfil['email']); ?>" required>
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($perfil['telefono']); ?>">
            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($perfil['direccion']); ?>">
            <label for="experiencia">Experiencia:</label>
            <textarea id="experiencia" name="experiencia"><?php echo htmlspecialchars($perfil['experiencia']); ?></textarea>
            <label for="educacion">Educación:</label>
            <textarea id="educacion" name="educacion"><?php echo htmlspecialchars($perfil['educacion']); ?></textarea>
            <label for="habilidades">Habilidades:</label>
            <textarea id="habilidades" name="habilidades"><?php echo htmlspecialchars($perfil['habilidades']); ?></textarea>
            <label for="imagen">Imagen de Perfil:</label>
            <input type="file" id="imagen" name="imagen">
            <?php if ($perfil['imagen']): ?>
                <img src="<?php echo htmlspecialchars($perfil['imagen']); ?>" alt="Imagen de Perfil" class="profile-img">
            <?php endif; ?>
            <button type="submit">Actualizar Perfil</button>
        </form>
    </main>
    <script>
        function toggleMenu() {
            document.getElementById('dropdown-menu').classList.toggle('show');
        }
    </script>
</body>
</html>
