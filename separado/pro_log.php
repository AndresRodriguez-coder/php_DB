<?php
// Iniciar sesión
session_start();

require_once 'conexion.php';
$conexion = getConexionMysqli();
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Sanitizar entradas
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$clave = $_POST['clave'];
$recordar = isset($_POST['recordar']) ? true : false;
// Validar campos
if (empty($email) || empty($clave)) {
$error = "Por favor, completa todos los campos";
} else {
// Buscar usuario por email
$stmt = $conexion->prepare(
"SELECT id, nombre, email, clave, estado, intentos_fallidos
FROM usuarios WHERE email = ?"
);
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();
if ($resultado->num_rows === 1) {
$usuario = $resultado->fetch_assoc();
// Verificar estado
if ($usuario['estado'] != 'activo') {
$error = "Esta cuenta ha sido suspendida o desactivada";
}
// Verificar intentos fallidos
elseif ($usuario['intentos_fallidos'] >= 5) {
$error = "Cuenta bloqueada por múltiples intentos fallidos";
}
// Verificar contraseña
elseif (password_verify($clave, $usuario['clave'])) {
// Contraseña correcta

// Actualizar último acceso e intentos fallidos
$stmt = $conexion->prepare(
"UPDATE usuarios
SET ultimo_acceso = NOW(), intentos_fallidos = 0
WHERE id = ?"
);
$stmt->bind_param("i", $usuario['id']);
$stmt->execute();
// Guardar datos en sesión
$_SESSION['usuario_id'] = $usuario['id'];
$_SESSION['usuario_nombre'] = $usuario['nombre'];
// Si eligió "recordarme", crear cookie
if ($recordar) {
$token = bin2hex(random_bytes(32));
$expiracion = time() + 60*60*24*30; // 30 días
$hash_token = password_hash($token, PASSWORD_DEFAULT);
$stmt = $conexion->prepare(
"UPDATE usuarios
SET token_recuperacion = ?, expiracion_token = FROM_UNIXTIME(?)
WHERE id = ?"
);
$stmt->bind_param("sii", $hash_token, $expiracion, $usuario['id']);
$stmt->execute();
setcookie("recordar", $usuario['id'].':'.$token,
$expiracion, "/", "", true, true);
}

// Redireccionar
header("Location: perfil.php");
exit();
} else {
// Contraseña incorrecta
$intentos = $usuario['intentos_fallidos'] + 1;
$stmt = $conexion->prepare(
"UPDATE usuarios SET intentos_fallidos = ? WHERE id = ?"
);
$stmt->bind_param("ii", $intentos, $usuario['id']);
$stmt->execute();
$error = "Correo o contraseña incorrectos";
}
} else {
$error = "Correo o contraseña incorrectos";
}
$stmt->close();
}
}

// Mostrar error si existe
if (!empty($error)) {
echo "<div class='error'><p>" . $error . "</p></div>";
}
$conexion->close();
?>