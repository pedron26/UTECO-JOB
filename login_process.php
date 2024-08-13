<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'uteco-job');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Obtener información del usuario y su tipo de cuenta
    $sql = "SELECT u.id, u.nombre, u.password, r.nombre AS rol, u.tipo_cuenta
            FROM usuarios u
            JOIN roles r ON u.rol_id = r.id
            WHERE u.email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['nombre'];
            $_SESSION['user_role'] = $row['rol'];  // Guardar el rol en la sesión
            $_SESSION['user_type'] = $row['tipo_cuenta'];  // Guardar el tipo de cuenta en la sesión

            // Redirigir según el tipo de cuenta y rol
            if ($row['rol'] == 'admin') {
                header("Location: portafolioadmin.php");
            } elseif ($row['tipo_cuenta'] == 'empresa') {
                header("Location: portafolioempresa.php");
            } else {
                header("Location: portafolio.php");
            }
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
}

$conn->close();
?>
