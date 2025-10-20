<?php
require_once 'conexion.php';

$conexion = getConexionMysqli();

$id = 1; // ID del registro a actualizar
$nombre = "Juan Pérez Actualizado";
$email = "juan.perez.actualizado@example.com";

$sql = "UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssi", $nombre, $email, $id);

if ($stmt->execute()) {
    echo "Registro actualizado exitosamente";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>