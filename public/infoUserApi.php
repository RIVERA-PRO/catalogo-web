<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Permitir solicitudes desde cualquier origen (no seguro para producción)

$servidor = "127.0.0.1:3306";
$usuario = "u605883457_adminuser";
$contrasena = "Makarovsi100";
$dbname = "u605883457_catalogos";

try {
    // Establecer conexión a la base de datos
    $dsn = "mysql:host=$servidor;dbname=$dbname";
    $conexion = new PDO($dsn, $usuario, $contrasena);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar la sesión del usuario
    session_start();
    if (!isset($_SESSION['usuario_id'])) {
        echo json_encode(["error" => "Usuario no autenticado"]);
        exit();
    }

    // Obtener el ID del usuario desde la sesión
    $usuarioId = $_SESSION['usuario_id'];

    // Consulta SQL para obtener datos del usuario
    $sqlSelectUsuario = "SELECT id, nombre, email, rol, imagen FROM `usuarios` WHERE id = :id";
    $stmtUsuario = $conexion->prepare($sqlSelectUsuario);
    $stmtUsuario->bindParam(':id', $usuarioId);
    
    if ($stmtUsuario->execute()) {
        // Verificar si hay resultados
        if ($stmtUsuario->rowCount() > 0) {
            // Obtener resultados
            $resultadoUsuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);

            // Convertir la imagen a Base64
            $imagenUsuario = $resultadoUsuario['imagen'];
            $resultadoUsuario['imagen'] = base64_encode($imagenUsuario);

            // Imprimir datos del usuario en formato JSON
            echo json_encode($resultadoUsuario);
        } else {
            echo json_encode(["error" => "Usuario no encontrado"]);
        }
    } else {
        // Imprimir mensaje de error si la ejecución de la consulta falla
        echo json_encode(["error" => "Error al ejecutar la consulta SQL: " . implode(", ", $stmtUsuario->errorInfo())]);
    }
} catch (PDOException $error) {
    // Manejar errores específicos de la conexión
    echo json_encode(["error" => "Error de conexión: " . $error->getMessage()]);
} catch (Exception $error) {
    // Manejar otros tipos de errores
    echo json_encode(["error" => "Error desconocido: " . $error->getMessage()]);
} finally {
    // Cerrar la conexión a la base de datos
    $conexion = null;
}
?>
