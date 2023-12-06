<?php
$servidor = "127.0.0.1:3306";
$usuario = "u605883457_adminuser";
$contrasena = "Makarovsi100";
$dbname = "u605883457_catalogos";
$mensaje = "";

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

                $mensaje = "Catálogo creado exitosamente.";
            } else {
                $mensaje = "Por favor, seleccione una imagen válida.";
            }
        } else {
            $mensaje = "Por favor, complete todos los campos del formulario.";
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
        // Verificar si se ha enviado una nueva imagen
        if (isset($_FILES['imagen_editar']) && $_FILES['imagen_editar']['error'] === UPLOAD_ERR_OK) {
            // Procesar y guardar la nueva imagen
            $imagenTempEditar = $_FILES['imagen_editar']['tmp_name'];
            $imagenDatosEditar = file_get_contents($imagenTempEditar);

            $imagenTempEditar2 = $_FILES['imagen_editar2']['tmp_name'];
            $imagenDatosEditar2 = file_get_contents($imagenTempEditar2);

            $imagenTempEditar3 = $_FILES['imagen_editar3']['tmp_name'];
            $imagenDatosEditar3 = file_get_contents($imagenTempEditar3);

            $imagenTempEditar4 = $_FILES['imagen_editar4']['tmp_name'];
            $imagenDatosEditar4 = file_get_contents($imagenTempEditar4);

            // Actualizar la base de datos con la nueva imagen
            $sqlUpdate = "UPDATE `catalogos` SET nombre = :nombre, descripcion = :descripcion, categoria= :categoria, imagen = :imagen, imagen2 = :imagen2 , imagen3 = :imagen3, imagen4 = :imagen4, precio=:precio WHERE id = :id";
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
        } else {
            // Procesar y guardar la información sin actualizar la imagen
            $sqlUpdate = "UPDATE `catalogos` SET nombre = :nombre, descripcion = :descripcion, categoria= :categoria, precio=:precio WHERE id = :id";
            $stmt = $conexion->prepare($sqlUpdate);
            $stmt->bindParam(':nombre', $nombreEditar);
            $stmt->bindParam(':descripcion', $descripcionEditar);
            $stmt->bindParam(':categoria', $categoriaEditar);
            $stmt->bindParam(':precio', $precioEditar);
            $stmt->bindParam(':id', $idEditar);
            $stmt->execute();
        }
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
    <title>Crear, Editar y Eliminar Catálogo</title>
    <style>
        /* Agrega estilos CSS para el modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Crear Nuevo Catálogo</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
       


        <br>
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea>
        <br>
         <label for="categoria">Categoria:</label>
        <textarea id="categoria" name="categoria" required></textarea>
        <br>
     
         <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" required >
        <br>
        <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*" required>
        <br>

        <label for="imagen2">Imagen2:</label>
        <input type="file" id="imagen2" name="imagen2" accept="image/*" required>
        <br>
        
        <label for="imagen3">Imagen3:</label>
        <input type="file" id="imagen3" name="imagen3" accept="image/*" required>
        <br>
        
        <label for="imagen4">Imagen4:</label>
        <input type="file" id="imagen4" name="imagen4" accept="image/*" required>
        <br>
        <button type="submit">Crear Catálogo</button>
    </form>

    <div>
        <?php echo $mensaje; ?>
    </div>

    <h2>Catálogos Existentes</h2>
    <?php if ($resultado): ?>
        <table border="1">
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
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="editar" value="<?php echo $catalogo['id']; ?>">
                <label for="nombre_editar">Nombre:</label>
                <input type="text" id="nombre_editar" name="nombre_editar" value="<?php echo $catalogo['nombre']; ?>" required>
                <br>
                <label for="descripcion_editar">Descripción:</label>
                <textarea id="descripcion_editar" name="descripcion_editar" required><?php echo $catalogo['descripcion']; ?></textarea>
                <br>
                <label for="categoriaEditar">Categoria:</label>
                <textarea id="categoriaEditar" name="categoriaEditar" required><?php echo $catalogo['categoria']; ?></textarea>
                <br>
                <label for="precioEditar">Precio:</label>
                <textarea id="precioEditar" name="precioEditar" required><?php echo $catalogo['precio']; ?></textarea>
                <br>
                <label for="imagen_editar">Imagen:</label>
                <input type="file" id="imagen_editar" name="imagen_editar" accept="image/*">
                <br>
                <label for="imagen_editar2">Imagen2:</label>
                <input type="file" id="imagen_editar2" name="imagen_editar2" accept="image/*">
                <br>
                <label for="imagen_editar3">Imagen4:</label>
                <input type="file" id="imagen_editar3" name="imagen_editar3" accept="image/*">
                <br>
                <label for="imagen_editar4">Imagen4:</label>
                <input type="file" id="imagen_editar4" name="imagen_editar4" accept="image/*">
                <br>
                <button type="submit">Guardar</button>
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
                <button onclick="openModal('<?php echo $catalogo['id']; ?>', '<?php echo $catalogo['nombre']; ?>', '<?php echo $catalogo['descripcion']; ?>')">Editar</button>
                </td>
                    <td>
                        <a href="?eliminar=<?php echo $catalogo['id']; ?>" onclick="return confirm('¿Estás seguro que deseas eliminar este catálogo?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No se encontraron catálogos</p>
    <?php endif; ?>

    <script>
        // Funciones para mostrar/ocultar el modal
        function openModal(id, nombre, descripcion) {
            document.getElementById("myModal" + id).style.display = "block";
            document.getElementById("nombre_editar").value = nombre;
            document.getElementById("descripcion_editar").value = descripcion;
        }

        function closeModal(id) {
            document.getElementById("myModal" + id).style.display = "none";
        }
    </script>
</body>
</html>
