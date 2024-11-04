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
        header("Location: Login.php");
    }
    exit();
} else {
    unset($_SESSION['alerta_mostrada']);
}

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Usuario eliminado exitosamente.";
        header("Location: Lista_Usuario.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Error: ID de usuario no especificado.";
}

$conn->close();
?>
