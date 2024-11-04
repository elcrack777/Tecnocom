<?php
include 'conexion.php';
session_start();

// Inicializa la variable de mensaje
$mensaje = '';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    $mensaje = 'Por favor, inicia sesión para acceder a esta página.';
}

// Obtener el ID y rol del usuario
$usuario_id = $_SESSION['usuario_id'];
$rol_id = $_SESSION['rol_id'];

if ($rol_id != 1) { // Solo Admin puede acceder a esta página
    $mensaje = 'Acceso denegado.';
}

// Obtener el ID del pendiente a editar
$id = $_POST['id'];

// Obtener los datos del pendiente a editar
$sql_select = "SELECT * FROM pendientes WHERE id = ?";
$stmt_select = $conn->prepare($sql_select);
$stmt_select->bind_param("i", $id);
$stmt_select->execute();
$result = $stmt_select->get_result();
$row = $result->fetch_assoc();
$stmt_select->close();

// Manejar la actualización del pendiente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    // Actualizar pendiente
    $nombre_tecnico = $_POST['nombre_tecnico'];
    $cliente = $_POST['cliente'];
    $fecha_atencion = $_POST['fecha_atencion'];
    $fecha_reasignacion = $_POST['fecha_reasignacion'];
    $status = $_POST['status'];
    $motivo = $_POST['motivo'];

    //$sql_update = "UPDATE pendientes SET nombre_tecnico = ?, cliente = ?, fecha_atencion = ?, fecha_reasignacion = ?, status = ? WHERE id = ?";
    //$stmt_update = $conn->prepare($sql_update);
    //$stmt_update->bind_param("sssssi", $nombre_tecnico, $cliente, $fecha_atencion, $fecha_reasignacion, $status, $id);

    $sql_update = "UPDATE pendientes SET nombre_tecnico = ?, cliente = ?, fecha_atencion = ?, fecha_reasignacion = ?, status = ?, motivo = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssssi", $nombre_tecnico, $cliente, $fecha_atencion, $fecha_reasignacion, $status, $motivo, $id);


    if ($stmt_update->execute()) {
        $mensaje = 'Pendiente actualizado exitosamente.';
        // Redirigir a la lista de pendientes después de un breve retraso
        header("refresh:2;url=Lista_pendiente.php");
    } else {
        $mensaje = 'Error al actualizar pendiente: ' . $stmt_update->error;
    }
    $stmt_update->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pendiente</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="Img/Icon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Css/Editar_pendiente.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Balsamiq+Sans:ital,wght@0,400;0,700;1,400;1,700&family=Quicksand:wght@300..700&display=swap');
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="Editar_usuario.php">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="Lista_pendiente.php">Lista Pendientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Admin.php">Rol</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Crear_pendiente.php">Crear Pendientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Logout.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h2>Editar Pendiente</h2>

        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-success" role="alert">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>

        <form action="editar_pendiente.php" method="POST">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">

            <div class="form-group">
                <label for="nombre_tecnico">Nombre del Técnico:</label>
                <input type="text" class="form-control" name="nombre_tecnico" value="<?= $row['nombre_tecnico'] ?>" required>
            </div>

            <div class="form-group">
                <label for="cliente">Cliente:</label>
                <input type="text" class="form-control" name="cliente" value="<?= $row['cliente'] ?>" required>
            </div>

            <div class="form-group">
                <label for="motivo">Motivo:</label>
                <textarea class="form-control" name="motivo" rows="3" required><?= $row['motivo'] ?></textarea>
            </div>

            <div class="form-group">
                <label for="fecha_atencion">Fecha de Atención:</label>
                <input type="date" class="form-control" name="fecha_atencion" value="<?= $row['fecha_atencion'] ?>">
            </div>

            <div class="form-group">
                <label for="fecha_reasignacion">Fecha de Reasignación:</label>
                <input type="date" class="form-control" name="fecha_reasignacion" value="<?= $row['fecha_reasignacion'] ?>">
            </div>

            <div class="form-group margin-bottom">
                <label for="status">Estado:</label>
                <select class="form-control" name="status">
                    <option value="Pendiente" <?= $row['status'] == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                    <option value="En Progreso" <?= $row['status'] == 'En Progreso' ? 'selected' : '' ?>>En Progreso</option>
                    <option value="Atendido" <?= $row['status'] == 'Atendido' ? 'selected' : '' ?>>Atendido</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" name="action" value="update">Actualizar Pendiente</button>
            <a href="Lista_pendiente.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>