<?php
include 'conexion.php';

session_start();
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1) {
    if (!isset($_SESSION['alerta_mostrada'])) {
        echo "<script>
                alert('No tienes permiso para acceder a esta página.');
                window.location.href = 'Login.php';
              </script>";
        $_SESSION['alerta_mostrada'] = true;
    } else {
        header("Location: Admin.php");
    }
    exit();
} else {
    unset($_SESSION['alerta_mostrada']);
}

// Inicializar variables
$id = null;
$nombre = '';
$correo = '';
$telefono = '';
$rol_id = 1;
$mensaje = '';

// Comprobar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que el ID esté definido
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        // Recuperar los datos del usuario
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $nombre = $user['nombre'];
            $correo = $user['correo'];
            $telefono = $user['telefono'];
            $rol_id = $user['rol_id'];
        } else {
            $mensaje = "Usuario no encontrado.";
        }

        // Validar y actualizar el usuario
        $nombre_nuevo = $_POST['nombre'] ?? $nombre;
        $correo_nuevo = $_POST['correo'] ?? $correo;
        $telefono_nuevo = $_POST['telefono'] ?? $telefono;
        $rol_id_nuevo = $_POST['rol_id'] ?? $rol_id;

        // Procesar la carga de la imagen
        $fotoRuta = $user['fotografia']; // Ruta actual de la fotografía
        if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] == UPLOAD_ERR_OK) {
            $fotoNombre = $_FILES['fotografia']['name'];
            $fotoTmp = $_FILES['fotografia']['tmp_name'];
            $fotoRuta = 'Img/Users/' . basename($fotoNombre);

            if (!move_uploaded_file($fotoTmp, $fotoRuta)) {
                $mensaje = "Error: No se pudo subir la fotografía.";
            }
        }

        // Comprobar si el correo ya está en uso
        if ($nombre_nuevo && $correo_nuevo && $telefono_nuevo && $rol_id_nuevo) {
            // Consulta para verificar si el correo ya existe
            $sql_check = "SELECT * FROM usuarios WHERE correo = ? AND id != ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("si", $correo_nuevo, $id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                $mensaje = "Error: El correo electrónico ya está en uso por otro usuario.";
            } else {
                // Verificar si hay cambios en los datos
                if ($nombre != $nombre_nuevo || $correo != $correo_nuevo || $telefono != $telefono_nuevo || $rol_id != $rol_id_nuevo || $fotoRuta != $user['fotografia']) {
                    // Actualizar el usuario
                    $sql = "UPDATE usuarios SET nombre = ?, correo = ?, telefono = ?, rol_id = ?, fotografia = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssssi", $nombre_nuevo, $correo_nuevo, $telefono_nuevo, $rol_id_nuevo, $fotoRuta, $id);

                    if ($stmt->execute()) {
                        $mensaje = "Usuario actualizado exitosamente.";
                        // Redirigir a la lista de usuarios
                        header("Location: Lista_usuario.php?mensaje=" . urlencode($mensaje));
                        exit();
                    } else {
                        $mensaje = "Error: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $mensaje = "No se han realizado cambios en los datos del usuario.";
                }
            }
            $stmt_check->close();
        } else {
            $mensaje = "Error: Todos los campos son obligatorios.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Editar Usuario</title>
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
    <div class="container mt-5 pt-1">
        <h2>Editar Usuario</h2>

        <?php if ($mensaje): ?>
            <div class="alert alert-dismissible fade show <?php echo strpos($mensaje, 'Error') === false ? 'alert-success' : 'alert-danger'; ?>" role="alert">
                <?php echo htmlspecialchars($mensaje); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <form action="Editar_usuario.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo Electrónico:</label>
                <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($correo); ?>" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($telefono); ?>" required>
            </div>
            <div class="form-group margin-bottom">
                <label for="rol_id">Rol:</label>
                <select class="form-control" id="rol_id" name="rol_id" required>
                    <option value="1" <?php if ($rol_id == 1) echo 'selected'; ?>>Admin</option>
                    <option value="2" <?php if ($rol_id == 2) echo 'selected'; ?>>Técnico</option>
                    <option value="3" <?php if ($rol_id == 3) echo 'selected'; ?>>Usuario</option>
                </select>
            </div>
            <div class="form-group">
                <label for="fotografia">Fotografía:</label>
                <input type="file" class="form-control" id="fotografia" name="fotografia" accept="image/*">
                <small class="form-text text-muted">Deja este campo vacío si no deseas cambiar la fotografía.</small>
            </div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                <a href="Lista_usuario.php" class="btn btn-secondary">Regresar</a>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>