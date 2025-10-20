<?php
require_once 'conexion.php';
$conexion = getConexionMysqli();
// Inicializar array de errores
$errores = [];

// Procesar formulario enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Sanitizar entradas
$nombre = htmlspecialchars(trim($_POST['nombre']));
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$clave = $_POST['clave'];
$clave2 = $_POST['clave2'];
// Validar nombre
if (empty($nombre)) {
$errores[] = "El nombre es obligatorio";
} elseif (strlen($nombre) < 2) {
$errores[] = "El nombre debe tener al menos 2 caracteres";
}
// Validar email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
$errores[] = "Email no válido";
}
// Verificar si el email ya existe
$stmt = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
$errores[] = "Este email ya está registrado";
}
$stmt->close();
// Validar contraseña
if (strlen($clave) < 8) {
$errores[] = "La contraseña debe tener al menos 8 caracteres";
} elseif (!preg_match("/[A-Z]/", $clave)) {
$errores[] = "La contraseña debe incluir al menos una letra mayúscula";
} elseif (!preg_match("/[a-z]/", $clave)) {
$errores[] = "La contraseña debe incluir al menos una letra minúscula";
} elseif (!preg_match("/[0-9]/", $clave)) {
$errores[] = "La contraseña debe incluir al menos un número";
}
// Verificar que las contraseñas coincidan
if ($clave !== $clave2) {
$errores[] = "Las contraseñas no coinciden";
}
// Si no hay errores, proceder con el registro
if (empty($errores)) {
// Hashear la contraseña
$hash = password_hash($clave, PASSWORD_DEFAULT);
// Insertar el nuevo usuario
$stmt = $conexion->prepare(
"INSERT INTO usuarios (nombre, email, clave) VALUES (?, ?, ?)"
);
$stmt->bind_param("sss", $nombre, $email, $hash);
if ($stmt->execute()) {
// Registro exitoso
$usuario_id = $stmt->insert_id;
// Iniciar sesión automáticamente
session_start();
$_SESSION['usuario_id'] = $usuario_id;
$_SESSION['usuario_nombre'] = $nombre;
// Redireccionar
header("Location: perfil.php");
exit();
} else {
$errores[] = "Error al registrar usuario: " . $stmt->error;
}
$stmt->close();
}
}
// Si hay errores, mostrarlos
if (!empty($errores)) {
echo "<div class='errores'>";
foreach ($errores as $error) {
echo "<p>" . $error . "</p>";
}
echo "</div>";
echo "<p><a href='javascript:history.back()'>Volver</a></p>";
}
$conexion->close();
?>