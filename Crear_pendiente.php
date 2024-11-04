<?php
include 'conexion.php'; // Asegúrate de incluir tu archivo de conexión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    echo "<script>alert('Por favor, inicia sesión para acceder a esta página.'); window.location.href='login.php';</script>";
    exit();
}

$rol_id = $_SESSION['rol_id']; // Asumiendo que el rol_id 1 es Admin
$mensaje = ""; // Variable para almacenar mensajes

// Manejar la creación de pendientes
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $rol_id == 1) {
    $nombre_tecnico = $_POST['nombre_tecnico'];
    $cliente = $_POST['cliente'];
    $fecha_atencion = $_POST['fecha_atencion'];
    $fecha_reasignacion = $_POST['fecha_reasignacion'];
    $status = $_POST['status'];
    $motivo = $_POST['motivo']; // Captura el motivo

    $sql_create = "INSERT INTO pendientes (nombre_tecnico, cliente, fecha_atencion, fecha_reasignacion, status, motivo) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_create = $conn->prepare($sql_create);
    $stmt_create->bind_param("ssssss", $nombre_tecnico, $cliente, $fecha_atencion, $fecha_reasignacion, $status, $motivo); // Incluye el motivo

    if ($stmt_create->execute()) {
        $mensaje = "Pendiente creado exitosamente.";
    } else {
        $mensaje = "Error al crear pendiente: " . $stmt_create->error;
    }
    $stmt_create->close();
}

// Obtener la lista de técnicos
$sql_tecnicos = "SELECT id, nombre FROM usuarios WHERE rol_id = 2"; // Asumiendo que el rol_id 2 es para Técnicos
$result_tecnicos = $conn->query($sql_tecnicos);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Pendiente</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="Img/Icon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Css/Crear_pendiente.css">
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
                        <a class="nav-link" href="Crear_usuario.php">Crear Usuario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Logout.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h2>Crear Pendiente</h2>

        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-success"><?= $mensaje ?></div>
        <?php endif; ?>

        <form action="Crear_pendiente.php" method="POST">
            <div class="form-group">
                <label for="nombre_tecnico">Nombre del Técnico:</label>
                <select class="form-control" name="nombre_tecnico" required>
                    <option value="" disabled selected>Selecciona un técnico</option>
                    <?php if ($result_tecnicos->num_rows > 0): ?>
                        <?php while ($row = $result_tecnicos->fetch_assoc()): ?>
                            <option value="<?php echo $row['nombre']; ?>"><?php echo $row['nombre']; ?></option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="cliente">Cliente:</label>
                <input type="text" class="form-control" name="cliente" required>
            </div>
            <div class="form-group">
                <label for="motivo">Motivo:</label> 
                <textarea class="form-control" name="motivo" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="fecha_atencion">Fecha de Atención:</label>
                <input type="date" class="form-control" name="fecha_atencion" required>
            </div>
            <div class="form-group">
                <label for="fecha_reasignacion">Fecha de Reasignación:</label>
                <input type="date" class="form-control" name="fecha_reasignacion">
            </div>
            <div class="form-group margin-bottom">
                <label for="status">Estado:</label>
                <select class="form-control" name="status">
                    <option value="Pendiente">Pendiente</option>
                    <option value="En Progreso">En Progreso</option>
                    <option value="Atendido">Atendido</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Crear Pendiente</button>
            <a href="Lista_pendiente.php" class="btn btn-secondary">Ver Lista de Pendientes</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>