<?php
$servidor = "127.0.0.1:3306";
$usuario = "u605883457_adminuser";
$contrasena = "Makarovsi100";
$dbname = "u605883457_catalogos";
$mensaje = "";




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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $categoria = $_POST['categoria'];
        $precio = $_POST['precio'];
        if (!empty($nombre) && !empty($descripcion)) {
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $imagenTemp = $_FILES['imagen']['tmp_name'];
                $imagenDatos = file_get_contents($imagenTemp);

                $imagenTemp2 = $_FILES['imagen2']['tmp_name'];
                $imagenDatos2 = file_get_contents($imagenTemp2);

                $imagenTemp3 = $_FILES['imagen3']['tmp_name'];
                $imagenDatos3 = file_get_contents($imagenTemp3);

                $imagenTemp4 = $_FILES['imagen4']['tmp_name'];
                $imagenDatos4 = file_get_contents($imagenTemp4);

                $sqlInsert = "INSERT INTO `catalogos` (nombre, descripcion,categoria, imagen,imagen2,imagen3,imagen4, precio) VALUES (:nombre, :descripcion,:categoria, :imagen, :imagen2, :imagen3, :imagen4, :precio)";
                $stmt = $conexion->prepare($sqlInsert);
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':descripcion', $descripcion);
                $stmt->bindParam(':categoria', $categoria);
                $stmt->bindParam(':imagen', $imagenDatos, PDO::PARAM_LOB);
                $stmt->bindParam(':imagen2', $imagenDatos2, PDO::PARAM_LOB);
                $stmt->bindParam(':imagen3', $imagenDatos3, PDO::PARAM_LOB);
                $stmt->bindParam(':imagen4', $imagenDatos4, PDO::PARAM_LOB);
                $stmt->bindParam(':precio', $precio);
                $stmt->execute();

                $mensaje = "Producto creado exitosamente.";
            } else {
                $mensaje = "Por favor, seleccione una imagen válida.";
            }
        } else {
            $mensaje = "Editado correctamente";
        }
    }

    if (isset($_GET['eliminar']) && is_numeric($_GET['eliminar'])) {
        $idEliminar = $_GET['eliminar'];
        $sqlDelete = "DELETE FROM `catalogos` WHERE id = :id";
        $stmt = $conexion->prepare($sqlDelete);
        $stmt->bindParam(':id', $idEliminar);
        $stmt->execute();
    }

   
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
        $idEditar = $_POST['editar'];
        $nombreEditar = $_POST['nombre_editar'];
        $descripcionEditar = $_POST['descripcion_editar'];
        $categoriaEditar = $_POST['categoriaEditar'];
        $precioEditar = $_POST['precioEditar'];
    
        // Verificar si se ha enviado una nueva imagen principal
        if (isset($_FILES['imagen_editar']) && $_FILES['imagen_editar']['error'] === UPLOAD_ERR_OK) {
            // Procesar y guardar la nueva imagen principal
            $imagenTempEditar = $_FILES['imagen_editar']['tmp_name'];
            $imagenDatosEditar = file_get_contents($imagenTempEditar);
        } else {
            // Mantener la imagen principal existente si no se envía una nueva
            $sqlImagenExistente = "SELECT imagen FROM `catalogos` WHERE id = :id";
            $stmtImagenExistente = $conexion->prepare($sqlImagenExistente);
            $stmtImagenExistente->bindParam(':id', $idEditar);
            $stmtImagenExistente->execute();
            $resultadoImagenExistente = $stmtImagenExistente->fetch(PDO::FETCH_ASSOC);
    
            $imagenDatosEditar = $resultadoImagenExistente['imagen'];
        }
    
        // Verificar y procesar las otras imágenes si se han enviado
        foreach (range(2, 4) as $imagenNumero) {
            $nombreCampoImagen = "imagen_editar{$imagenNumero}";
    
            if (isset($_FILES[$nombreCampoImagen]) && $_FILES[$nombreCampoImagen]['error'] === UPLOAD_ERR_OK) {
                $imagenTempEditar = $_FILES[$nombreCampoImagen]['tmp_name'];
                $nombreParametroImagen = ":imagen{$imagenNumero}";
    
                // Procesar y guardar la nueva imagen
                ${"imagenDatosEditar{$imagenNumero}"} = file_get_contents($imagenTempEditar);
            } else {
                // Mantener la imagen existente si no se envía una nueva
                $nombreCampoImagenExistente = "imagen{$imagenNumero}";
    
                $sqlImagenExistente = "SELECT {$nombreCampoImagenExistente} FROM `catalogos` WHERE id = :id";
                $stmtImagenExistente = $conexion->prepare($sqlImagenExistente);
                $stmtImagenExistente->bindParam(':id', $idEditar);
                $stmtImagenExistente->execute();
                $resultadoImagenExistente = $stmtImagenExistente->fetch(PDO::FETCH_ASSOC);
    
                ${"imagenDatosEditar{$imagenNumero}"} = $resultadoImagenExistente[$nombreCampoImagenExistente];
            }
        }
    
        // Actualizar la base de datos con las nuevas imágenes y la información
        $sqlUpdate = "UPDATE `catalogos` SET nombre = :nombre, descripcion = :descripcion, categoria = :categoria, 
                      imagen = :imagen, imagen2 = :imagen2, imagen3 = :imagen3, imagen4 = :imagen4, precio = :precio 
                      WHERE id = :id";
        $stmt = $conexion->prepare($sqlUpdate);
        $stmt->bindParam(':nombre', $nombreEditar);
        $stmt->bindParam(':descripcion', $descripcionEditar);
        $stmt->bindParam(':categoria', $categoriaEditar);
        $stmt->bindParam(':imagen', $imagenDatosEditar, PDO::PARAM_LOB);
        $stmt->bindParam(':imagen2', $imagenDatosEditar2, PDO::PARAM_LOB);
        $stmt->bindParam(':imagen3', $imagenDatosEditar3, PDO::PARAM_LOB);
        $stmt->bindParam(':imagen4', $imagenDatosEditar4, PDO::PARAM_LOB);
        $stmt->bindParam(':precio', $precioEditar);
        $stmt->bindParam(':id', $idEditar);
        $stmt->execute();
    }
    

    $sqlSelect = "SELECT * FROM `catalogos`";
    $sentencia = $conexion->prepare($sqlSelect);
    $sentencia->execute();
    $resultado = $sentencia->fetchAll();

} catch (PDOException $error) {
    $mensaje = "Error de conexión: " . $error->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./logo.png" />
    <link rel="stylesheet" href="styleAdmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


    <title>Fauguet Admin</title>
  
   
</head>
<body>

<nav class="navbarDashboard" onclick="toggleNavbarWidth()">
<img src="./logo2.png" alt="logo">

        <a href="" id="dashboard-link"><span class="icon"><i class="fas fa-home"></i></span>Inicio</a>
        <a href="usuarios.php" id="usuarios-link"><span class="icon"><i class="fas fa-user"></i></span>Usuarios</a>
    </nav>

    <div class="table-container">
<div id="crearCatalogoModal" class="modal">
    <div class="modal-content">
     
       <span class="close" onclick="closeCrearCatalogoModal()">&times;</span>
      
  
    <form   class="form-modal" action="" method="POST" enctype="multipart/form-data">
       <div class="inputs">
       <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required  placeholder="Nombre">
       </div>
       


      
      
  
     <div class="inputs">
       <label for="categoria">Categoria:</label>
<select id="categoria" name="categoria" required>
    <option value="notebook">Notebook</option>
    <option value="auricular">Auricular</option>
    <option value="celular">Celular</option>
    <option value="teclado">Teclado</option>
    <option value="mouse">Mouse</option>
</select>
       </div>

     
     
       <div class="inputs">
      <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" required  placeholder="Precio">
   
      </div>
      <div class="inputs">
     <label for="descripcion">Descripción:</label>
        <input id="descripcion" name="descripcion" required  placeholder="Descripción">
     </div>
      <div class="inputs">
       <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*" required>
       </div>
      
       
       <div class="inputs">
    <label for="imagen2">Imagen2:</label>
        <input type="file" id="imagen2" name="imagen2" accept="image/*" required>
    </div>
        
       
    <div class="inputs">
<label for="imagen3">Imagen3:</label>
        <input type="file" id="imagen3" name="imagen3" accept="image/*" required>
</div>
   
<div class="inputs">
             
       <label for="imagen4">Imagen4:</label>
        <input type="file" id="imagen4" name="imagen4" accept="image/*" required>
       </div>
        <button  class="btn" type="submit">Crear catálogo</button>
    </form>

    </div>
</div>
  
    <button class="btn_crear" onclick="openCrearCatalogoModal()">Agregar</button>

   
    <?php if ($resultado): ?>
        <table  class="table">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Categoria</th>
                <th>Precio</th>
                <th>Imagen</th>
                <th>Imagen2</th>
                <th>Imagen3</th>
                <th>Imagen4</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($resultado as $catalogo): ?>
                <tr>
                    <td><?php echo $catalogo['id']; ?></td>
                    <div id="myModal<?php echo $catalogo['id']; ?>" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('<?php echo $catalogo['id']; ?>')">&times;</span>
            <form  class="form-modal" action="" method="POST" enctype="multipart/form-data" >
        
                <div class="inputs">
                <input type="hidden" name="editar" value="<?php echo $catalogo['id']; ?>">
                </div>
                <div class="inputs">
             <label for="nombre_editar">Nombre:</label>
                <input type="text" id="nombre_editar" name="nombre_editar" value="<?php echo $catalogo['nombre']; ?>" required>
             </div>
           
             <div class="inputs">
               <label for="descripcion_editar">Descripción:</label>
                <input id="descripcion_editar" name="descripcion_editar"  value="<?php echo $catalogo['descripcion']; ?>"  required>
               </div>
               
               <div class="inputs">
               <label for="categoriaEditar">Categoria:</label>
<select id="categoriaEditar" name="categoriaEditar" required>
    <option value="notebook" <?php echo ($catalogo['categoria'] === 'notebook') ? 'selected' : ''; ?>>Notebook</option>
    <option value="auricular" <?php echo ($catalogo['categoria'] === 'auriculares') ? 'selected' : ''; ?>>Auricular</option>
    <option value="celular" <?php echo ($catalogo['categoria'] === 'celulares') ? 'selected' : ''; ?>>Celular</option>
    <option value="teclado" <?php echo ($catalogo['categoria'] === 'teclado') ? 'selected' : ''; ?>>Teclado</option>
       <option value="mouse" <?php echo ($catalogo['categoria'] === 'mouse') ? 'selected' : ''; ?>>Mouse</option>
</select>

               </div>
              
               <div class="inputs">
             <label for="precioEditar">Precio:</label>
                <input id="precioEditar" name="precioEditar" value="<?php echo $catalogo['precio']; ?>" required>
             </div>
             
               
             <div class="inputs">
            <label for="imagen_editar">Imagen:</label>
                <input type="file" id="imagen_editar" name="imagen_editar" accept="image/*">
            </div>
              
        
            <div class="inputs">
            <label for="imagen_editar2">Imagen2:</label>
                <input type="file" id="imagen_editar2" name="imagen_editar2" accept="image/*">
            </div>
             
            <div class="inputs">
             <label for="imagen_editar3">Imagen4:</label>
                <input type="file" id="imagen_editar3" name="imagen_editar3" accept="image/*">
             </div>
               
             <div class="inputs">
           <label for="imagen_editar4">Imagen4:</label>
                <input type="file" id="imagen_editar4" name="imagen_editar4" accept="image/*">
           </div>
                <button class="btn" type="submit">Guardar</button>
            </form>
        </div>
    </div>
                    <td><?php echo $catalogo['nombre']; ?></td>
                    <td><?php echo $catalogo['descripcion']; ?></td>
                        <td><?php echo $catalogo['categoria']; ?></td>
                        <td><?php echo $catalogo['precio']; ?></td>
                    <td><img src='data:image/jpeg;base64,<?php echo base64_encode($catalogo['imagen']); ?>' alt='Imagen del catálogo' width='100'></td>


                    <td><img src='data:image/jpeg;base64,<?php echo base64_encode($catalogo['imagen2']); ?>' alt='Imagen del catálogo' width='100'></td>

                    <td><img src='data:image/jpeg;base64,<?php echo base64_encode($catalogo['imagen3']); ?>' alt='Imagen del catálogo' width='100'></td>
                    <td><img src='data:image/jpeg;base64,<?php echo base64_encode($catalogo['imagen4']); ?>' alt='Imagen del catálogo' width='100'></td>
               
                    <td>
                    <button class="editar_btn" onclick="openModal('<?php echo $catalogo['id']; ?>', '<?php echo $catalogo['nombre']; ?>', '<?php echo $catalogo['descripcion']; ?>')"> <span class="action-icon"><i class="fas fa-edit"></i></button>
                      <button class="eliminar_btn" >
                      <a href="?eliminar=<?php echo $catalogo['id']; ?>" onclick="return confirm('¿Estás seguro que deseas eliminar este catálogo?')"> <span class="action-icon"><i
                                        class="fas fa-trash"></i></span></a>
                      </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No se encontraron catálogos</p>
    <?php endif; ?>
</div>

    <script>
        // Funciones para mostrar/ocultar el modal
     function openModal(id) {
    console.log("Abriendo modal para el ID:", id);
    document.getElementById("myModal" + id).style.display = "block";
}




        function closeModal(id) {
            document.getElementById("myModal" + id).style.display = "none";
        }
        function openCrearCatalogoModal() {
        document.getElementById("crearCatalogoModal").style.display = "block";
    }

    function closeCrearCatalogoModal() {
        document.getElementById("crearCatalogoModal").style.display = "none";
    }

    var currentPage = window.location.pathname.split('/').pop().toLowerCase();

// Asignar la clase "active" al enlace correspondiente
if (currentPage === 'dashboard.php' || currentPage === '') {
    document.getElementById('dashboard-link').classList.add('active');
} else if (currentPage === 'productos.html') {
    document.getElementById('productos-link').classList.add('active');
} else if (currentPage === 'usuarios.html') {
    document.getElementById('usuarios-link').classList.add('active');
}


    </script>
    <?php if (!empty($mensaje)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'sucess',
                title: 'Mensaje',
                text: "<?php echo $mensaje; ?>",
            });
        });
    </script>
<?php endif; ?>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


</body>
</html>
