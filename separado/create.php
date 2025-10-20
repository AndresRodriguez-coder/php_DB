<?php
require_once 'conexion.php';

$conexion = getConexionMysqli();

$nombre = "Juan Perez";
$email = "juan.perez@example.com";

$sql = "INSERT INTO usuarios (nombre, email) VALUES (?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ss", $nombre, $email);

if ($stmt->execute()) {
    echo "Nuevo registro creado exitosamente";
    $id_insertado = $stmt->insert_id;
    echo "ID del nuevo registro: " . $id_insertado;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>