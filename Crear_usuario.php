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

$mensaje = '';

if (isset($_POST['nombre'], $_POST['correo'], $_POST['telefono'], $_POST['contraseña'], $_POST['rol_id'])) {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
    $rol_id = $_POST['rol_id'];

    // Verificamos si el correo ya existe
    $checkEmail = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = ?");
    $checkEmail->bind_param("s", $correo);
    $checkEmail->execute();
    $checkEmail->bind_result($emailCount);
    $checkEmail->fetch();
    $checkEmail->close();

    // Verificamos si el teléfono ya existe
    $checkPhone = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE telefono = ?");
    $checkPhone->bind_param("s", $telefono);
    $checkPhone->execute();
    $checkPhone->bind_result($phoneCount);
    $checkPhone->fetch();
    $checkPhone->close();

    // Procesar la carga de la imagen
    $fotoRuta = '';
    if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] == UPLOAD_ERR_OK) {
        $fotoNombre = $_FILES['fotografia']['name'];
        $fotoTmp = $_FILES['fotografia']['tmp_name'];
        $fotoRuta = 'Img/Users/' . basename($fotoNombre); // Define la ruta donde se almacenará la imagen

        if (move_uploaded_file($fotoTmp, $fotoRuta)) {
            // Imagen cargada exitosamente
        } else {
            $mensaje = "error: No se pudo subir la fotografía.";
        }
    }

    // Si no hay duplicados, se inserta el nuevo usuario
    if ($emailCount > 0) {
        $mensaje = "error: El correo ya está registrado.";
    } elseif ($phoneCount > 0) {
        $mensaje = "error: El teléfono ya está registrado.";
    } else {
        $sql = "INSERT INTO usuarios (nombre, correo, telefono, contraseña, rol_id, fotografia) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssis", $nombre, $correo, $telefono, $contraseña, $rol_id, $fotoRuta);

        if ($stmt->execute()) {
            $mensaje = "Usuario creado exitosamente."; // Mensaje de éxito
        } else {
            $mensaje = "error: " . $stmt->error; // Mensaje de error
        }

        $stmt->close();
    }
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $mensaje = "warning: Todos los campos son obligatorios.";
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
    <title>Crear Usuario</title>
    <link rel="icon" href="Img/Icon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Css/Crear_usuario.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Balsamiq+Sans:ital,wght@0,400;0,700;1,400;1,700&family=Quicksand:wght@300..700&display=swap');
    </style>
</head>

<body>
    <script src="JS/Script.js"></script>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="Crear_usuario.php">Admin Panel</a>
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
                        <a class="nav-link" href="Crear_clientes.php">Crear Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Logout.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Crear Usuario</h2>

        <?php if ($mensaje): ?>
            <div class="alert <?= strpos($mensaje, 'success') === 0 ? 'alert-success' : 'alert-danger' ?>">
                <?= str_replace('error:', '', str_replace('success:', '', $mensaje)) ?>
            </div>
        <?php endif; ?>

        <form action="Crear_usuario.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" name="nombre" id="nombre" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" class="form-control" name="correo" id="correo" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" class="form-control" name="telefono" id="telefono" required>
            </div>
            <div class="form-group">
                <label for="contraseña">Contraseña:</label>
                <input type="password" class="form-control" name="contraseña" id="contraseña" required>
            </div>
            <div class="form-group margin-bottom">
                <label for="rol_id">Rol:</label>
                <select class="form-control" name="rol_id" id="rol_id" required>
                    <option value="1">Admin</option>
                    <option value="2">Técnico</option>
                    <option value="3">Usuario</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="fotografia" class="form-label">Fotografía</label>
                <input type="file" name="fotografia" id="fotografia" class="form-control" accept="image/*">
            </div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary">Crear Usuario</button>
                <a href="Lista_usuario.php" class="btn btn-secondary">Regresar</a>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>