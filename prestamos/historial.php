<?php

require_once __DIR__ . "/../conexion.php";

$json = array();

/** Quitar el p.fecha_devolucion > NOW() si quiero todos e incluso los que estan retrasados */
$sql = "SELECT p.id, 
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
               p.fecha_devolucion,
               p.estatus,
               p.perder
        FROM prestamos p 
        INNER JOIN lectores le ON p.lector_id = le.id
        INNER JOIN libros li ON p.libro_id = li.id
        INNER JOIN usuarios u ON p.usuario_id = u.id
        INNER JOIN autores a ON li.autor_id = a.id
        ORDER BY p.id DESC";

$mysql = $conexion->query($sql);

if ($mysql->num_rows > 0) {
    while ($datos = $mysql->fetch_assoc()) {
        $json["prestamos"][] = $datos;
    }
} else {
    $resultado = array(
        "id" => 0,
        "lector_nombre" => "No hay registro",
        "lector_apellido" => "No hay registro",
        "lector_correo" => "No hay registro",
        "lector_telefono" => "No hay registro",
        "lector_direccion" => "No hay registro",
        "libro_isbn" => "No hay registro",
        "libro_titulo" => "No hay registro",
        "autor_nombre" => "No hay registro",
        "usuario_nombre" => "No hay registro",
        "usuario_apellido" => "No hay registro",
        "fecha_prestamo" => "No hay registro",
        "fecha_devolucion" => "No hay registro",
        "retraso" => 0
    );
    $json["prestamos"][] = $resultado;
}

$mysql->close();
echo json_encode($json, JSON_UNESCAPED_UNICODE);