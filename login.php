<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sección - UTECO JOB</title>
    <link rel="stylesheet" href="Css/logincss.css">
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
            <form action="login_process.php" method="POST">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Iniciar Sección</button>
                <button type="button" onclick="window.location.href='google_login.php'">Iniciar con Google</button>
            </form>
        </div>
    </main>
</body>
</html>
