<?php
include 'conexion.php';

//if (!isset($_SESSION['usuario_id'])) {
//echo "<script>alert('Por favor, inicia sesión para acceder a esta página.'); window.location.href='Login.php';</script>";
//exit();
//}

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

$usuario_id = $_SESSION['usuario_id'];
$rol_id = $_SESSION['rol_id'];

// Inicializar la variable de estado
$selected_status = isset($_POST['status']) ? $_POST['status'] : '';

// Obtener todos los pendientes filtrados por estado
$sql_select = "SELECT * FROM pendientes";
if ($selected_status) {
    $sql_select .= " WHERE status = ?";
}

$stmt_select = $conn->prepare($sql_select);
if ($selected_status) {
    $stmt_select->bind_param("s", $selected_status);
}
$stmt_select->execute();
$result = $stmt_select->get_result();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Editar pendiente
    if (isset($_POST['action']) && $_POST['action'] == 'edit' && $rol_id == 1) {
        $id = $_POST['id'];
        $nombre_tecnico = $_POST['nombre_tecnico'];
        $cliente = $_POST['cliente'];
        $status = $_POST['status'];

        $sql_edit = "UPDATE pendientes SET nombre_tecnico = ?, cliente = ?, status = ? WHERE id = ?";
        $stmt_edit = $conn->prepare($sql_edit);
        $stmt_edit->bind_param("sssi", $nombre_tecnico, $cliente, $status, $id);

        if ($stmt_edit->execute()) {
            echo "<script>alert('Pendiente actualizado exitosamente.');</script>";
        } else {
            echo "<script>alert('Error al actualizar pendiente: " . $stmt_edit->error . "');</script>";
        }
        $stmt_edit->close();
    }

    // Eliminar pendiente
    if (isset($_POST['action']) && $_POST['action'] == 'delete' && $rol_id == 1) {
        $id = $_POST['id'];

        $sql_delete = "DELETE FROM pendientes WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $id);

        if ($stmt_delete->execute()) {
            echo "<script>alert('Pendiente eliminado exitosamente.');</script>";
        } else {
            echo "<script>alert('Error al eliminar pendiente: " . $stmt_delete->error . "');</script>";
        }
        $stmt_delete->close();
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Pendientes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="Img/Icon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Css/Lista_pendiente.css">
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
        <h2>Lista de Pendientes</h2>

        <!-- Formulario para filtrar por estado -->
        <form method="POST" class="mb-4">
            <div class="form-group">
                <label for="status">Filtrar por Estado:</label>
                <select name="status" id="status" class="form-control" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    <option value="Pendiente" <?= $selected_status == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                    <option value="En Progreso" <?= $selected_status == 'En Progreso' ? 'selected' : '' ?>>En Progreso</option>
                    <option value="Atendido" <?= $selected_status == 'Atendido' ? 'selected' : '' ?>>Atendido</option>
                </select>
            </div>
        </form>

        <table class="table table-striped table-bordered table-hover table-custom">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha de Creación</th>
                    <th>Nombre del Técnico</th>
                    <th>Cliente</th>
                    <th>Motivo</th>
                    <th>Fecha de Atención</th>
                    <th>Fecha de Reasignación</th>
                    <th>Estado</th>
                    <?php if ($rol_id == 1): // Solo Admin puede editar y eliminar 
                    ?>
                        <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['fecha_creacion'] ?></td>
                        <td><?= $row['nombre_tecnico'] ?></td>
                        <td><?= $row['cliente'] ?></td>
                        <td><?= $row['motivo'] ?></td>
                        <td><?= $row['fecha_atencion'] ?></td>
                        <td><?= $row['fecha_reasignacion'] ?></td>

                        <td>
                            <?php
                            // Colorear el estado según su valor
                            switch ($row['status']) {
                                case 'Pendiente':
                                    $statusClass = 'status-pendiente';
                                    break;
                                case 'En Progreso':
                                    $statusClass = 'status-en-progreso';
                                    break;
                                case 'Atendido':
                                    $statusClass = 'status-atendido';
                                    break;
                                default:
                                    $statusClass = 'status-default';
                            }
                            ?>
                            <span class="<?= $statusClass ?>"><?= $row['status'] ?></span>
                        </td>
                        <?php if ($rol_id == 1): // Solo Admin puede editar y eliminar 
                        ?>
                            <td>
                                <form action="Editar_pendiente.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn btn-primary">Editar</button>
                                </form>
                                <form action="Lista_pendiente.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="Crear_pendiente.php" class="btn btn-primary">Crear Nuevo Pendiente</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>