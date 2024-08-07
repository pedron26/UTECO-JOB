<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'uteco-job');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Obtener información del usuario y rol
    $sql = "SELECT u.id, u.nombre, u.password, r.nombre AS rol
            FROM usuarios u
            JOIN roles r ON u.rol_id = r.id
            WHERE u.email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['nombre'];
            $_SESSION['user_role'] = $row['rol'];  // Guardar el rol en la sesión

            // Redirigir según el rol
            if ($row['rol'] == 'admin') {
                header("Location: portafolioadmin.php");
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
