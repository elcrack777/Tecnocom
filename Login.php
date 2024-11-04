<?php
include 'conexion.php';

session_start();

if (isset($_POST['correo']) && isset($_POST['contraseña'])) {
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    /* $sql = "SELECT id, contraseña, rol_id FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password, $rol_id); */

    $sql = "SELECT id, nombre, correo, contraseña, rol_id FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $nombre, $correo, $hashed_password, $rol_id);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($contraseña, $hashed_password)) {
            // Ingreso exitoso
            $_SESSION['usuario_id'] = $id;
            $_SESSION['nombre'] = $nombre; // Guardar el nombre
            $_SESSION['correo'] = $correo; // Guardar el corre
            $_SESSION['rol_id'] = $rol_id;
            header("Location: Admin.php");
            exit();
        } else {
            // Contraseña incorrecta
            echo "<script>alert('Contraseña incorrecta');</script>";
        }
    } else {
        // Correo no encontrado
        echo "<script>alert('Correo no encontrado');</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Por favor, ingresa tus datos para iniciar sesión.');</script>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="icon" href="Img/Icon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Css/Login.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Balsamiq+Sans:ital,wght@0,400;0,700;1,400;1,700&family=Quicksand:wght@300..700&display=swap');
    </style>
</head>

<body>
    <script src="JS/Script.js"></script>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-6 col-lg-4">
            <img src="Img/Icon.png" alt="Foto de perfil" class="profile-img">
            <h2 class="text-center mb-4">
                <span class="fw-bold">Dash</span>board
            </h2>

            <form id="loginForm" action="" method="POST" class="p-4 border rounded shadow-sm bg-light">
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo:</label>
                    <input type="email" name="correo" id="correo" class="form-control" placeholder="Ingresa tu correo" required>
                </div>
                <div class="mb-3">
                    <label for="contraseña" class="form-label">Contraseña:</label>
                    <input type="password" name="contraseña" id="contraseña" class="form-control" placeholder="Ingresa tu contraseña" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            let correo = document.getElementById('correo').value;
            let contraseña = document.getElementById('contraseña').value;

            if (correo === '' || contraseña === '') {
                event.preventDefault();
                alert('Por favor, completa todos los campos.');
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>