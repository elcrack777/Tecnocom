<?php
include 'control_acceso.php';
verificarAcceso(1); // Solo Admin puede acceder (rol_id = 1)
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Panel de Administración</title>
    <link rel="icon" href="Img/Icon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Css/Admin.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Balsamiq+Sans:ital,wght@0,400;0,700;1,400;1,700&family=Quicksand:wght@300..700&display=swap');
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="Admin.php">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="Perfil_usuario.php">Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Logout.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center header-title">Panel de Administración</h1>
        <div class="row">
            <div class="col-md-4">
                <div class="card card-custom text-center mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Usuarios</h5>
                        <p class="card-text">Crear usuarios.</p>
                        <a href="Crear_usuario.php" class="btn btn-primary">Crear Usuario</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom text-center mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Pendientes</h5>
                        <p class="card-text">Crear pendientes.</p>
                        <a href="Crear_pendiente.php" class="btn btn-primary">Crear Pendiente</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom text-center mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Clientes</h5>
                        <p class="card-text">Crear clientes.</p>
                        <a href="" class="btn btn-primary">Crear Cliente</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom text-center mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Usuarios</h5>
                        <p class="card-text">Ver y gestionar usuarios.</p>
                        <a href="Lista_Usuario.php" class="btn btn-primary">Ver Usuarios</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom text-center mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Pendientes</h5>
                        <p class="card-text">Ver y gestionar pendientes.</p>
                        <a href="Lista_pendiente.php" class="btn btn-primary">Ver Pendientes</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom text-center mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Clientes</h5>
                        <p class="card-text">Ver y gestionar clientes.</p>
                        <a href="" class="btn btn-primary">Ver Clientes</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>