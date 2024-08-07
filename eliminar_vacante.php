<?php
include('db.php');

if (isset($_GET['id'])) {
    $vacante_id = $_GET['id'];
    $sql = "DELETE FROM vacantes WHERE id = $vacante_id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Vacante eliminada con éxito'); window.location.href='ver_vacantes.php';</script>";
    } else {
        echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
} else {
    echo "<p>ID de vacante no especificado.</p>";
}

$conn->close();
?>
