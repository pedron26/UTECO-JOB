<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - UTECO JOB</title>
    <link rel="stylesheet" href="Css/registerylogin.css">
</head>
<body>
    <header>
        <img src="IMG/Logo.jpeg" alt="Logo UTECO" class="logo">
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="quienes_somos.php">Quienes Somos</a></li>
                <li><a href="register.php">Unirse a Nosotros</a></li>
                <li><a href="login.php">Iniciar Sección</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="form-container">
            <img src="IMG/Logo.jpeg" alt="Logo UTECO" class="form-logo">
            <h1>Bienvenido a UTECO JOB</h1>
            <form action="register_process.php" method="POST">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Registrarse</button>
            </form>
        </div>
    </main>
</body>
</html>
