<?php
require_once 'conexion.php';

$conexion = getConexionMysqli();

$id = 1; // ID del registro a eliminar

$sql = "DELETE FROM usuarios WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Registro eliminado exitosamente";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>