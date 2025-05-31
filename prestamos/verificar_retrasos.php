<?php
require_once __DIR__ . "/../conexion.php";

$json = array();

$sqlAtrasos = "SELECT p.id, 
                      le.nombre AS 'lector_nombre', 
                      le.apellido AS 'lector_apellido', 
                      le.correo AS 'lector_correo', 
                      le.telefono AS 'lector_telefono', 
                      le.direccion AS 'lector_direccion', 
                      li.isbn AS 'libro_isbn', 
                      li.titulo AS 'libro_titulo', 
                      a.nombre AS 'autor_nombre', 
                      u.nombre AS 'usuario_nombre', 
                      u.apellido AS 'usuario_apellido', 
                      p.fecha_prestamo, 
                      p.fecha_devolucion 
               FROM prestamos p 
               INNER JOIN lectores le ON p.lector_id = le.id
               INNER JOIN libros li ON p.libro_id = li.id
               INNER JOIN usuarios u ON p.usuario_id = u.id
               INNER JOIN autores a ON li.autor_id = a.id
               WHERE p.fecha_devolucion < NOW() AND p.estatus = 1 AND p.perder = 0
               ORDER BY p.id DESC";

$resultAtrasos = $conexion->query($sqlAtrasos);

if ($resultAtrasos->num_rows > 0) {
    $atrasos = array();

    while ($datos = $resultAtrasos->fetch_assoc()) {
        $datos["retraso"] = true;
        $atrasos[] = $datos;
    }

    $json = array(
        "status" => true,
        "message" => "Existen libros con retrasos de entrega. Consulta el apartado de Retrasos",
        "atrasos" => $atrasos
    );
} else {

    $no[] = array(
        "id" => 0,
        "lector_nombre" => "No hay libro atrasado",
        "lector_apellido" => "No hay libro atrasado",
        "lector_correo" => "No hay libro atrasado",
        "lector_telefono" => "No hay libro atrasado",
        "lector_direccion" => "No hay libro atrasado",
        "libro_isbn" => "No hay libro atrasado",
        "libro_titulo" => "No hay libro atrasado",
        "autor_nombre" => "No hay libro atrasado",
        "usuario_nombre" => "No hay libro atrasado",
        "usuario_apellido" => "No hay libro atrasado",
        "fecha_prestamo" => "No hay libro atrasado",
        "fecha_devolucion" => "No hay libro atrasado",
        "retraso" => false
    );

    $json = array(
        "status" => false,
        "message" => "No hay libros atrasados",
        "atrasos" => $no
    );
}

$resultAtrasos->close();
$conexion->close();

echo json_encode($json, JSON_UNESCAPED_UNICODE);
