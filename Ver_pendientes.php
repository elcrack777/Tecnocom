<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Pendientes</title>
    <link rel="stylesheet" href="Css/Pendientes.css">
</head>
<body>
    <header>
        <h1>Gestión de Pendientes</h1>
    </header>
    <main>
        <div class="notification" id="notification"></div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha Creación</th>
                    <th>Técnico Asignado</th>
                    <th>Cliente</th>
                    <th>Fecha Atención</th>
                    <th>Fecha Reasignación</th>
                    <th>Status</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se generarán los registros de pendientes con PHP -->
                <?php
                // Ejemplo de registro de pendiente
                echo "
                <tr>
                    <td>1</td>
                    <td>2024-11-01</td>
                    <td>Juan Pérez</td>
                    <td>Cliente A</td>
                    <td>2024-11-02</td>
                    <td>2024-11-03</td>
                    <td><span class='status pendiente'>Pendiente</span></td>
                    <td><button class='btn-completar' onclick='actualizarEstado(1)'>Marcar como Atendido</button></td>
                </tr>";
                ?>
            </tbody>
        </table>
    </main>
    <script src="script.js"></script>
</body>
</html>
