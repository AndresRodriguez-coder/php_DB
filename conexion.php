<?php
$servidor = "localhost";
$usuario = "root";
$contraseña = "";
$basededatos = "mi_base_de_datos";

$conexion = mysqli_connect($servidor, $usuario, $contraseña, $basededatos);

if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}
echo "Conexión exitosa";
?>

<?php
$servidor = "localhost";
$usuario = "root";
$contraseña = "";
$basededatos = "mi_base_de_datos";

try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $contraseña);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa";
} catch (PDOException $e) {
    echo "Conexión fallida: " . $e->getMessage();
}
?>