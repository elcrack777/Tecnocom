<?php
include 'conexion.php';

$id = $_POST['id'];

$sql = "UPDATE pendientes SET status = 'Atendido' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Pendiente actualizado";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
