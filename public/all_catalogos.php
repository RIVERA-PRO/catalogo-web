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

    // Consulta SQL para obtener todos los catálogos
    $sqlSelect = "SELECT id, nombre, descripcion, categoria, imagen,  imagen2, imagen3 ,imagen4, precio FROM `catalogos`";
    $sentencia = $conexion->prepare($sqlSelect);

    if ($sentencia->execute()) {
        // Obtener resultados
        $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

        // Convertir imágenes a Base64
        foreach ($resultado as &$fila) {
             $imagen = $fila['imagen'];
    $imagen2 = $fila['imagen2'];
    $imagen3 = $fila['imagen3'];
    $imagen4 = $fila['imagen4'];

    $fila['imagen'] = base64_encode($imagen);
    $fila['imagen2'] = base64_encode($imagen2);
    $fila['imagen3'] = base64_encode($imagen3);
    $fila['imagen4'] = base64_encode($imagen4);
        }

        // Imprimir datos en formato JSON
        echo json_encode($resultado);
    } else {
        // Imprimir mensaje de error si la ejecución de la consulta falla
        echo json_encode(["error" => "Error al ejecutar la consulta SQL: " . implode(", ", $sentencia->errorInfo())]);
    }
} catch (PDOException $error) {
    // Manejar errores específicos de la conexión
    echo json_encode(["error" => "Error de conexión: " . $error->getMessage()]);
} catch (Exception $error) {
    // Manejar otros tipos de errores
    echo json_encode(["error" => "Error desconocido: " . $error->getMessage()]);
}
?>
