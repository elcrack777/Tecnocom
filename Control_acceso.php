<?php
session_start();

function verificarAcceso($rol_necesario) {
    if (!isset($_SESSION['rol_id'])) {
        header("Location: login.php"); // Redirige al login si no está autenticado
        exit;
    }

    // Si el rol del usuario es menor al rol necesario, redirige
    if ($_SESSION['rol_id'] < $rol_necesario) {
        header("Location: acceso_denegado.php"); // Redirige a una página de acceso denegado
        exit;
    }
}
?>
