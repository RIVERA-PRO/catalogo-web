<?php
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
                    header("Location: /");
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
    <link rel="stylesheet" href="login.css">
    <title>Fauguet - Iniciar Sesión</title>
</head>
<body>
   
   <div class="formContainer">
   <form action="" method="POST">
        <h2>Iniciar Sesión</h2>
      
        <input type="email" id="email_login" name="email_login" placeholder="Email"   required>
      
        <input type="password" id="contrasena_login" name="contrasena_login" placeholder="Contraseña" required>
        <button type="submit" name="iniciar_sesion">Iniciar Sesión</button>
  <div class="deFLex">
  <p>¿ No tienes una cuenta ?</p> <a href="registro.php">Registrarse</a>
  </div>
    </form>
   </div>

    <div>
        <?php echo $mensajeLogin; ?>
    </div>
</body>
</html>
