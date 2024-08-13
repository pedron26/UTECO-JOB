<?php
include('db.php');

// Definir la función para contar las solicitudes
function getSolicitudesCount() {
    global $conn;
    $sql = "SELECT COUNT(*) as count FROM solicitudes";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}

// Obtener las vacantes
$sql = "SELECT * FROM vacantes";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Vacantes - UTECO_JOB</title>
    <link rel="stylesheet" href="Css/ver_vacantes.css"> <!-- Vincular el archivo CSS -->
</head>
<body>
    <div class="sidebar">
        <img src="IMG/Logo.jpeg" alt="Logo UTECO">
        <a href="inicio.php">Inicio</a>
        <a href="control_usuarios.php">Control de Usuarios</a>
        <a href="portafolioadmin.php">Publicar Vacante</a>
        <a href="ver_vacantes.php">Ver Vacantes</a>
        <a href="ver_solicitudes.php">Solicitudes<span class="counter"><?php echo getSolicitudesCount(); ?></span></a>
        <a href="logout.php">Cerrar Sesión</a>
    </div>
    <div class="container">
        <h1>Ver Vacantes</h1>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="vacante">
                    <h2><?php echo $row['titulo']; ?></h2>
                    <p><strong>Descripción:</strong> <?php echo $row['descripcion']; ?></p>
                    <p><strong>Fecha de Apertura:</strong> <?php echo $row['fecha_apertura']; ?></p>
                    <p><strong>Fecha de Cierre:</strong> <?php echo $row['fecha_cierre']; ?></p>
                    <?php if ($row['imagen']) { ?>
                        <img src="uploads/<?php echo $row['imagen']; ?>" alt="<?php echo $row['titulo']; ?>">
                    <?php } ?>
                    <div class="actions">
                        <a href="editar_vacante.php?id=<?php echo $row['id']; ?>" class="btn">Editar</a>
                        <a href="eliminar_vacante.php?id=<?php echo $row['id']; ?>" class="btn btn-delete">Eliminar</a>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No hay vacantes disponibles.</p>";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
