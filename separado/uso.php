<?php
require_once 'conexion.php';

$conexion = getConexionMysqli();

// Aquí puedes realizar consultas a la base de datos utilizando $conexion

$conexion->close();
?>