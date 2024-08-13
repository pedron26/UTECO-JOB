<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - UTECO JOB</title>
    <link rel="stylesheet" href="Css/logincss.css">
    <script>
        function toggleEmpresaFields() {
            const tipoCuenta = document.getElementById('tipo_cuenta').value;
            const empresaFields = document.getElementById('empresa-fields');
            empresaFields.style.display = tipoCuenta === 'empresa' ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <header>
        <img src="IMG/Logo.jpeg" alt="Logo UTECO" class="logo">
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="quienes_somos.php">Quienes Somos</a></li>
                <li><a href="register.php">Registrarse</a></li>
                <li><a href="login.php">Iniciar Sección</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="form-container">
            <img src="IMG/Logo.jpeg" alt="Logo UTECO" class="form-logo">
            <h1>Registrarse en UTECO JOB</h1>
            <form action="register_process.php" method="POST">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>

                <label for="tipo_cuenta">Tipo de Cuenta:</label>
                <select id="tipo_cuenta" name="tipo_cuenta" onchange="toggleEmpresaFields()">
                    <option value="usuario">Usuario</option>
                    <option value="empresa">Empresa</option>
                </select>

                <!-- Campos adicionales para empresa -->
                <div id="empresa-fields" style="display:none;">
                    <label for="nombre_empresa">Nombre de la Empresa:</label>
                    <input type="text" id="nombre_empresa" name="nombre_empresa">

                    <label for="direccion_empresa">Dirección de la Empresa:</label>
                    <input type="text" id="direccion_empresa" name="direccion_empresa">

                    <label for="telefono_empresa">Teléfono de la Empresa:</label>
                    <input type="text" id="telefono_empresa" name="telefono_empresa">

                    <label for="sector_empresa">Sector de la Empresa:</label>
                    <input type="text" id="sector_empresa" name="sector_empresa">
                </div>

                <button type="submit">Registrarse</button>
            </form>
        </div>
    </main>
</body>
</html>
