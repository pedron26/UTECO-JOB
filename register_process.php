<?php
// Incluir la conexión a la base de datos
include 'db.php';

// Procesar el formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $tipo_cuenta = $_POST['tipo_cuenta'];

    // Determinar el estado del usuario
    $estado = ($tipo_cuenta === 'empresa') ? 'pendiente' : 'activo';

    // Asignar rol_id basado en tipo_cuenta
    $rol_id = ($tipo_cuenta === 'empresa') ? 3 : 2;

    // Insertar en la tabla usuarios
    $query = "INSERT INTO usuarios (nombre, email, password, rol_id, tipo_cuenta, estado) VALUES ('$nombre', '$email', '$password', '$rol_id', '$tipo_cuenta', '$estado')";

    if (mysqli_query($conn, $query)) {
        // Obtener el ID del nuevo usuario
        $usuario_id = mysqli_insert_id($conn);

        // Si es una empresa, insertar en la tabla empresas
        if ($tipo_cuenta === 'empresa') {
            $nombre_empresa = $_POST['nombre_empresa'];
            $direccion_empresa = $_POST['direccion_empresa'];
            $telefono_empresa = $_POST['telefono_empresa'];
            $sector_empresa = $_POST['sector_empresa'];

            $query_empresa = "INSERT INTO empresas (usuario_id, nombre_empresa, direccion_empresa, telefono_empresa, sector_empresa) VALUES ('$usuario_id', '$nombre_empresa', '$direccion_empresa', '$telefono_empresa', '$sector_empresa')";

            if (!mysqli_query($conn, $query_empresa)) {
                echo "Error al insertar datos de la empresa: " . mysqli_error($conn);
                exit();
            }
        }

        // Redirigir al usuario a la página adecuada
        if ($tipo_cuenta === 'empresa') {
            header("Location: cuenta_en_proceso.php");
        } else {
            header("Location: portafolio.php");
        }
        exit();
    } else {
        echo "Error al registrar el usuario: " . mysqli_error($conn);
    }
}
?>
