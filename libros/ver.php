<?php

require_once __DIR__ . "/../conexion.php";

$json = array();

if (isset($_GET["id"])) {
    $id = intval($_GET["id"]); // Convertir el ID a entero para mayor seguridad

    $sql = "SELECT l.id as 'libro_id', l.isbn, l.titulo, a.nombre as 'autor_nombre', l.anio_publicacion, e.nombre as 'editorial_nombre', c.nombre as 'categoria_nombre', c.pasillo, l.stoke FROM libros l
            INNER JOIN autores a ON l.autor_id = a.id
            INNER JOIN editoriales e ON l.editorial_id = e.id
            INNER JOIN categorias c ON l.categoria_id = c.id
            WHERE l.id = {$id}";
            
    $mysql = $conexion->query($sql);

    if ($mysql->num_rows > 0) {
        $json["libro"] = $mysql->fetch_assoc();
    } else {
        $json["libro"] = array(
            "libro_id" => 0,
            "isbn" => "No hay registro",
            "titulo" => "No hay registro",
            "autor_nombre" => "No hay registro",
            "anio_lanzamiento" => 0,
            "editorial_nombre" => "No hay registro",
            "categoria_nombre" => "No hay registro",
            "pasillo" => 0,
            "stoke" => 0
        );
    }

    $mysql->close();
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
}