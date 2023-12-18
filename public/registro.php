<?php
$servidor = "127.0.0.1:3306";
$usuario = "u605883457_adminuser";
$contrasena = "Makarovsi100";
$dbname = "u605883457_catalogos";
$mensajeUsuario = "";



try {
    $dsn = "mysql:host=$servidor;dbname=$dbname";
    $conexion = new PDO($dsn, $usuario, $contrasena);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_usuario'])) {
        $nombreUsuario = $_POST['nombre_usuario'];
        $emailUsuario = $_POST['email_usuario'];
        $contrasenaUsuario = password_hash($_POST['contrasena_usuario'], PASSWORD_DEFAULT);

        // Establecer el valor de rol como "usuario"
        $rolUsuario = "usuario";

        if (!empty($nombreUsuario) && !empty($emailUsuario) && !empty($contrasenaUsuario) && !empty($rolUsuario)) {
            if (isset($_FILES['imagen_usuario']) && $_FILES['imagen_usuario']['error'] === UPLOAD_ERR_OK) {
                $imagenTempUsuario = $_FILES['imagen_usuario']['tmp_name'];
                $imagenDatosUsuario = file_get_contents($imagenTempUsuario);

                $sqlInsertUsuario = "INSERT INTO `usuarios` (nombre, email, contrasena, rol, imagen) VALUES (:nombre, :email, :contrasena, :rol, :imagen)";
                $stmtUsuario = $conexion->prepare($sqlInsertUsuario);
                $stmtUsuario->bindParam(':nombre', $nombreUsuario);
                $stmtUsuario->bindParam(':email', $emailUsuario);
                $stmtUsuario->bindParam(':contrasena', $contrasenaUsuario);
                $stmtUsuario->bindParam(':rol', $rolUsuario);
                $stmtUsuario->bindParam(':imagen', $imagenDatosUsuario, PDO::PARAM_LOB);
                $stmtUsuario->execute();

                $mensajeUsuario = "Usuario creado exitosamente.";
            } else {
                $mensajeUsuario = "Por favor, seleccione una imagen válida.";
            }
        } else {
            $mensajeUsuario = "Por favor, complete todos los campos del formulario de usuario.";
        }
    }
} catch (PDOException $error) {
    $mensajeUsuario = "Error de conexión: " . $error->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Fauguet Admin - Crear Usuario</title>
</head>
<body>
   
<div class="formContainer">
   <form action="" method="POST" enctype="multipart/form-data">
    <h2>Registrarse</h2>
       
        <input type="text" id="nombre_usuario" name="nombre_usuario" required placeholder="Nombre">

      
      
        <input type="email" id="email_usuario" name="email_usuario" required placeholder="Email">

        
      
        <input type="password" id="contrasena_usuario" name="contrasena_usuario" required placeholder="Contraseña">

      
        <input type="hidden" id="rol_usuario" name="rol_usuario" value="usuario">

     
      
        <input type="file" id="imagen_usuario" name="imagen_usuario" accept="image/*" required placeholder="Imagen">

        
        <button type="submit" name="crear_usuario">Registrar</button>
       
        <div class="deFLex">
        <p>¿ Ya tienes una cuenta ?</p> <a href="login.php">Iniciar Sesion</a>
  </div>
    </form>
   </div>

    <div>
        <?php echo $mensajeUsuario; ?>
    </div>
</body>
</html>
