<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo "<script>alert('Por favor, inicia sesión para acceder a esta página.'); window.location.href='Login.php';</script>";
    exit();
}

$mensaje = '';

$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT nombre, correo, telefono, rol_id, fotografia FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($nombre, $correo, $telefono, $rol_id, $fotografia);
$stmt->fetch();
$stmt->close();

$isAdmin = ($_SESSION['rol_id'] == 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] == UPLOAD_ERR_OK) {
        $foto_temp = $_FILES['fotografia']['tmp_name'];
        $foto_nombre = $_FILES['fotografia']['name'];
        $foto_destino = 'Img/Users/' . $foto_nombre;

        move_uploaded_file($foto_temp, $foto_destino);
    } else {
        $foto_destino = $fotografia;
    }

    $sql_update = "UPDATE usuarios SET nombre = ?, correo = ?, telefono = ?, fotografia = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssi", $nombre, $correo, $telefono, $foto_destino, $usuario_id);

    if ($stmt_update->execute()) {
        $mensaje = 'Usuario actualizado exitosamente.'; // Asignar mensaje de éxito
    } else {
        $mensaje = 'Error al actualizar los datos: ' . $stmt_update->error; // Mensaje de error
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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Perfil de Admin</title>
    <link rel="icon" href="Img/Icon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Css/Editar_Usuario.css">
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
                        <a class="nav-link" href="Lista_usuario.php">Lista Usuarios</a>
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
        <h2>Perfil de Usuario</h2>
        <?php if ($mensaje): ?>
            <div class="alert <?= strpos($mensaje, 'Error') === false ? 'alert-success' : 'alert-danger' ?>">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($correo); ?>" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="tel" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($telefono); ?>" required>
            </div>
            <div class="mb-3">
                <label for="fotografia" class="form-label">Fotografía</label>
                <input type="file" class="form-control" id="fotografia" name="fotografia">
                <?php if ($fotografia): ?>
                    <img src="<?php echo htmlspecialchars($fotografia); ?>" alt="Foto de perfil" class="img-thumbnail mt-2" style="max-width: 150px;">
                <?php endif; ?>
            </div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>

        </form>
    </div>
</body>

</html>