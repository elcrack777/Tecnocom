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

// Obtener todos los usuarios
$sql = "SELECT * FROM usuarios";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Gestión de Usuarios</title>
    <link rel="icon" href="Img/Icon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Css/Lista_usuario.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Balsamiq+Sans:ital,wght@0,400;0,700;1,400;1,700&family=Quicksand:wght@300..700&display=swap');
    </style>
</head>

<body>
    <script src="JS/Script.js"></script>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="Editar_usuario.php">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="Crear_pendiente.php">Crear Pendientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Admin.php">Rol</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Lista_usuario.php">Ver Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Logout.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center mb-4">
            <span class="fw-bold">Gestión </span>de Usuarios
        </h2>

        <!-- Mensaje de éxito -->
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-success" role="alert">
                <?php
                echo $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);
                ?>
            </div>
        <?php endif; ?>

        <table class="table table-striped table-bordered table-hover table-custom">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <tbody>
                <?php while ($user = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['nombre']; ?></td>
                        <td><?php echo $user['correo']; ?></td>
                        <td><?php echo $user['telefono']; ?></td>
                        <td>
                            <?php
                            switch ($user['rol_id']) {
                                case 1:
                                    echo "Admin";
                                    break;
                                case 2:
                                    echo "Técnico";
                                    break;
                                case 3:
                                    echo "Usuario";
                                    break;
                            }
                            ?>
                        </td>
                        <td>
                            <a href="javascript:void(0);" onclick="submitEditForm(<?php echo $user['id']; ?>)" class="btn btn-primary">Editar Usuario</a>
                            <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $user['id']; ?>)" class="btn btn-danger">Eliminar Usuario</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>

            <form id="editForm" action="Editar_usuario.php" method="POST" style="display: none;">
                <input type="hidden" name="id" id="editUserId">
            </form>

            <form id="deleteForm" action="Eliminar_usuario.php" method="POST" style="display: none;">
                <input type="hidden" name="id" id="deleteUserId">
            </form>

            <script>
                function submitEditForm(id) {
                    document.getElementById('editUserId').value = id;
                    document.getElementById('editForm').submit();
                }

                function confirmDelete(id) {
                    if (confirm("¿Estás seguro de que deseas eliminar este usuario?")) {
                        document.getElementById('deleteUserId').value = id;
                        document.getElementById('deleteForm').submit();
                    }
                }
            </script>

            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php
$conn->close();
?>