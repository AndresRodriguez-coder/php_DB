<?php
require_once 'config.php';

function getConexionMysqli() {
    $conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $conexion->set_charset(DB_CHARSET);
    return $conexion;
}