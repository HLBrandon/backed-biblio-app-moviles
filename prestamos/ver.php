<?php

require_once __DIR__ . "/../conexion.php";

$json = array();

if (isset($_GET['id'])) {

    $id = intval($_GET['id']);

    $sql = "SELECT p.id, 
                   p.estatus, 
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
            WHERE p.id = $id";

    $mysql = $conexion->query($sql);

    if ($mysql->num_rows > 0) {

        $datos = $mysql->fetch_assoc();

        if ($datos["estatus"] == 0) {
            $resultado = array(
                "id" => 0,
                "lector_nombre" => "Libro ya fue devuelto",
                "lector_apellido" => "Libro ya fue devuelto",
                "lector_correo" => "Libro ya fue devuelto",
                "lector_telefono" => "Libro ya fue devuelto",
                "lector_direccion" => "Libro ya fue devuelto",
                "libro_isbn" => "Libro ya fue devuelto",
                "libro_titulo" => "Libro ya fue devuelto",
                "autor_nombre" => "Libro ya fue devuelto",
                "usuario_nombre" => "Libro ya fue devuelto",
                "usuario_apellido" => "Libro ya fue devuelto",
                "fecha_prestamo" => "Libro ya fue devuelto",
                "fecha_devolucion" => "Libro ya fue devuelto"
            );
            $json["prestamo"] = $resultado;
        } else {
            $json["prestamo"] = $datos;
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
            "fecha_devolucion" => "No hay registro"
        );
        $json["prestamo"] = $resultado;
    }

    $mysql->close();
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
}