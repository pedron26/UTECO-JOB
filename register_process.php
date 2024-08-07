<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'uteco-job');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO usuarios (nombre, email, password, rol_id) VALUES ('$nombre', '$email', '$password', (SELECT id FROM roles WHERE nombre='user'))";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['user_name'] = $nombre;
        header("Location: portafolio.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
