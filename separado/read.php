<?php 
require_once 'conexion.php';

$conexion = getConexionMysqli();

$sql = "SELECT id, nombre, email FROM usuarios";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $fila['id'] . "</td>";
        echo "<td>" . $fila['nombre'] . "</td>";
        echo "<td>" . $fila['email'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}

$resultado->free();
$conexion->close();
?>