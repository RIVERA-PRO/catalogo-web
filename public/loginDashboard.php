<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servidor = "127.0.0.1:3306";
$usuario = "u605883457_adminuser";
$contrasena = "Makarovsi100";
$dbname = "u605883457_catalogos";
$mensajeLogin = "";

try {
    $dsn = "mysql:host=$servidor;dbname=$dbname";
    $conexion = new PDO($dsn, $usuario, $contrasena);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['iniciar_sesion'])) {
        $emailLogin = $_POST['email_login'];
        $contrasenaLogin = $_POST['contrasena_login'];
    
        // Verificar las credenciales del usuario
        $sqlCheckCredenciales = "SELECT id, contrasena, rol FROM `usuarios` WHERE email = :email";
        $stmtCheckCredenciales = $conexion->prepare($sqlCheckCredenciales);
        $stmtCheckCredenciales->bindParam(':email', $emailLogin);
        $stmtCheckCredenciales->execute();
    
        if ($stmtCheckCredenciales->rowCount() > 0) {
            $row = $stmtCheckCredenciales->fetch(PDO::FETCH_ASSOC);
            $contrasenaHash = $row['contrasena'];
    
            if (password_verify($contrasenaLogin, $contrasenaHash)) {
                // Iniciar sesión y redirigir al usuario según el rol
                session_start();
                $_SESSION['usuario_id'] = $row['id'];
                $_SESSION['rol'] = $row['rol'];
    
                if ($row['rol'] == 'admin') {
                    header("Location: dashboard.php");
                } else {
                    // Usuario no es admin, mostrar mensaje de error
                    $mensajeLogin = "Error: No tienes permisos de administrador.";
                }
                exit();
            } else {
                $mensajeLogin = "Error: Contraseña incorrecta.";
            }
        } else {
            $mensajeLogin = "Error: Usuario no encontrado.";
        }
    }
    
} catch (PDOException $error) {
    $mensajeLogin = "Error de conexión: " . $error->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Fauguet - Iniciar Sesión</title>
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <form action="" method="POST">
        <label for="email_login">Correo Electrónico:</label>
        <input type="email" id="email_login" name="email_login" required>

        <br>
        <label for="contrasena_login">Contraseña:</label>
        <input type="password" id="contrasena_login" name="contrasena_login" required>

        <br>
        <button type="submit" name="iniciar_sesion">Iniciar Sesión</button>
    </form>

    <div>
        <?php echo $mensajeLogin; ?>
    </div>
</body>
</html>
