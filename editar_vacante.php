<?php
include('db.php');

function getSolicitudesCount() {
    global $conn;
    $sql = "SELECT COUNT(*) as count FROM solicitudes";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Vacante - UTECO_JOB</title>
    <link rel="stylesheet" href="Css/editvacante.css"> <!-- Vincular el archivo CSS -->
</head>
<body>
    <div class="sidebar">
        <img src="IMG/Logo.jpeg" alt="Logo UTECO">
        <a href="inicio.php">Inicio</a>
        <a href="portafolioadmin.php">Agregar Vacante</a>
        <a href="ver_vacantes.php">Ver Vacantes</a>
        <a href="ver_solicitudes.php">Solicitudes<span class="counter"><?php echo getSolicitudesCount(); ?></span></a>
        <a href="logout.php">Cerrar Sesión</a>
    </div>
    <div class="container">
        <h1>Editar Vacante</h1>
        <?php
        if (isset($_GET['id'])) {
            $vacante_id = $_GET['id'];
            $sql = "SELECT * FROM vacantes WHERE id = $vacante_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                ?>
                <form action="editar_vacante.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $vacante_id; ?>">
                    <label for="titulo">Título:</label>
                    <input type="text" id="titulo" name="titulo" value="<?php echo $row['titulo']; ?>" required><br><br>

                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" required><?php echo $row['descripcion']; ?></textarea><br><br>

                    <label for="fecha_apertura">Fecha de Apertura:</label>
                    <input type="date" id="fecha_apertura" name="fecha_apertura" value="<?php echo $row['fecha_apertura']; ?>" required><br><br>

                    <label for="fecha_cierre">Fecha de Cierre:</label>
                    <input type="date" id="fecha_cierre" name="fecha_cierre" value="<?php echo $row['fecha_cierre']; ?>" required><br><br>

                    <label for="imagen">Imagen:</label>
                    <input type="file" id="imagen" name="imagen" accept="image/*"><br><br>
                    <img src="uploads/<?php echo $row['imagen']; ?>" alt="<?php echo $row['titulo']; ?>" width="100"><br><br>

                    <input type="submit" name="actualizar_vacante" value="Actualizar Vacante">
                </form>
                <?php
            } else {
                echo "<p>Vacante no encontrada.</p>";
            }
        } elseif (isset($_POST['actualizar_vacante'])) {
            $vacante_id = $_POST['id'];
            $titulo = $_POST['titulo'];
            $descripcion = $_POST['descripcion'];
            $fecha_apertura = $_POST['fecha_apertura'];
            $fecha_cierre = $_POST['fecha_cierre'];
            $imagen = $_FILES['imagen']['name'];
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($imagen);

            if ($imagen) {
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file)) {
                    $sql = "UPDATE vacantes SET titulo='$titulo', descripcion='$descripcion', fecha_apertura='$fecha_apertura', fecha_cierre='$fecha_cierre', imagen='$imagen' WHERE id=$vacante_id";
                } else {
                    echo "<p>Error al subir la nueva imagen.</p>";
                }
            } else {
                $sql = "UPDATE vacantes SET titulo='$titulo', descripcion='$descripcion', fecha_apertura='$fecha_apertura', fecha_cierre='$fecha_cierre' WHERE id=$vacante_id";
            }

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Vacante actualizada con éxito'); window.location.href='ver_vacantes.php';</script>";
            } else {
                echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
            }
        } else {
            echo "<p>ID de vacante no especificado.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
