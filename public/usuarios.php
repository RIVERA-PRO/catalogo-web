<?php
$servidor = "127.0.0.1:3306";
$usuario = "u605883457_adminuser";
$contrasena = "Makarovsi100";
$dbname = "u605883457_catalogos";
$mensajeUsuarios = "";

session_start();
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    // Redirigir al formulario de inicio de sesión o mostrar un mensaje de no autorizado
    header("Location: loginDashboard.php");
    exit();
}
try {
    $dsn = "mysql:host=$servidor;dbname=$dbname";
    $conexion = new PDO($dsn, $usuario, $contrasena);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['eliminar_usuario']) && is_numeric($_GET['eliminar_usuario'])) {
        $idEliminarUsuario = $_GET['eliminar_usuario'];
        $sqlDeleteUsuario = "DELETE FROM `usuarios` WHERE id = :id";
        $stmtUsuario = $conexion->prepare($sqlDeleteUsuario);
        $stmtUsuario->bindParam(':id', $idEliminarUsuario);
        $stmtUsuario->execute();
        $mensajeUsuarios = "Usuario eliminado exitosamente.";
    }

// Lógica para crear un nuevo usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_usuario'])) {
    $nombreUsuario = $_POST['nombre_usuario'];
    $emailUsuario = $_POST['email_usuario'];
    $contrasenaUsuario = password_hash($_POST['contrasena_usuario'], PASSWORD_DEFAULT);
    $rolUsuario = $_POST['rol_usuario'];

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

            $mensajeUsuarios = "Usuario creado exitosamente.";
        } else {
            $mensajeUsuarios = "Por favor, seleccione una imagen válida.";
        }
    } else {
        $mensajeUsuarios = "Por favor, complete todos los campos del formulario de usuario.";
    }
}


    $sqlSelectUsuarios = "SELECT * FROM `usuarios`";
    $sentenciaUsuarios = $conexion->prepare($sqlSelectUsuarios);
    $sentenciaUsuarios->execute();
    $resultadoUsuarios = $sentenciaUsuarios->fetchAll();
} catch (PDOException $error) {
    $mensajeUsuarios = "Error de conexión: " . $error->getMessage();
}



?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleAdmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Fauguet Admin -  Usuarios</title>
</head>
<body>
<nav class="navbarDashboard" onclick="toggleNavbarWidth()">
<img src="./logo2.png" alt="logo">

        <a href="dashboard.php" id="dashboard-link"><span class="icon"><i class="fas fa-home"></i></span>Inicio</a>
        <a href="usuarios.php" id="usuarios-link"><span class="icon"><i class="fas fa-user"></i></span>Usuarios</a>
    </nav>

    <div class="table-container">
    <button class="btn_crear" onclick="openCrearUsuarioModal()">Crear Usuario</button>
    <?php if ($resultadoUsuarios): ?>
        <table class="table">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($resultadoUsuarios as $usuario): ?>
                <tr>
                    <td><?php echo $usuario['id']; ?></td>
                    <td><?php echo $usuario['nombre']; ?></td>
                    <td><?php echo $usuario['email']; ?></td>
                    <td><?php echo $usuario['rol']; ?></td>
                    <td><img src='data:image/jpeg;base64,<?php echo base64_encode($usuario['imagen']); ?>' alt='Imagen del usuario' width='100'></td>

                    <td>
                       <button class="eliminar_btn" >
                       <a href="?eliminar_usuario=<?php echo $usuario['id']; ?>" onclick="return confirm('¿Estás seguro que deseas eliminar este usuario?')"><span class="action-icon"><i
                                        class="fas fa-trash"></i></span></a>
                       </button>
                        <button class="editar_btn" onclick="openEditarUsuarioModal('<?php echo $usuario['id']; ?>')"><span class="action-icon"><i class="fas fa-edit"></i></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No se encontraron usuarios</p>
    <?php endif; ?>

    <div id="editarUsuarioModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditarUsuarioModal()">&times;</span>
        <h2>Editar Usuario</h2>
       
        <form class="form-modal" id="formEditarUsuario" method="POST">
           
            <label for="rol_usuario_edit">Nuevo Rol:</label>
            <select id="rol_usuario_edit" name="rol_usuario_edit" required>
                <option value="admin">Admin</option>
                <option value="usuario">Usuario</option>
            </select>
           

            <br>
            <button type="button" onclick="editarUsuario()">Guardar Cambios</button>
        </form>
    </div>
</div>

    <div id="crearUsuarioModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeCrearUsuarioModal()">&times;</span>
          
    <form class="form-modal" action="" method="POST" enctype="multipart/form-data">
    <div class="inputs">
       <label for="nombre_usuario">Nombre:</label>
        <input type="text" id="nombre_usuario" name="nombre_usuario" required placeholder="Nombre">
       </div>

      
       <div class="inputs">
      <label for="email_usuario">Email:</label>
        <input type="email" id="email_usuario" name="email_usuario" required placeholder="Email">
      </div>

       
      <div class="inputs">
      <label for="contrasena_usuario">Contraseña:</label>
        <input type="password" id="contrasena_usuario" name="contrasena_usuario" required placeholder="Contraseña">

      </div>
      
      <div class="inputs">
        <label for="rol_usuario">Rol:</label>
<select id="rol_usuario" name="rol_usuario" required>
    <option value="admin">Admin</option>
    <option value="usuario">Usuario</option>
</select>
        </div>


        
     
        <div class="inputs">
      <label for="imagen_usuario">Imagen:</label>
        <input type="file" id="imagen_usuario" name="imagen_usuario" accept="image/*" required>
      </div>

      
        <button class="btn"  type="submit" name="crear_usuario">Crear Usuario</button>
    </form>

        </div>
    </div>

    
    </div>

   
   
    <?php if (!empty($mensajeUsuarios)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'sucess',
                title: 'Mensaje',
                text: "<?php echo $mensajeUsuarios; ?>",
            });
        });
    </script>
<?php endif; ?>
    <script>
        // Funciones para mostrar/ocultar el modal de Crear Usuario
        function openCrearUsuarioModal() {
            document.getElementById("crearUsuarioModal").style.display = "block";
        }

        function closeCrearUsuarioModal() {
            document.getElementById("crearUsuarioModal").style.display = "none";
        }
        
    </script>
</body>
</html>
