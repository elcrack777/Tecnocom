<?php
include 'conexion.php';

$id = $_POST['id'];

$sql = "DELETE FROM pendientes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Pendiente eliminado exitosamente";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
