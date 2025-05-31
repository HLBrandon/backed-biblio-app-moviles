<?php

require_once __DIR__ . "/../conexion.php";

$json = array();

if (isset($_GET["buscar"])) {

    $buscar = $_GET["buscar"];

    $sql = "SELECT l.id as 'libro_id', l.isbn, l.titulo, a.nombre as 'autor_nombre', l.anio_publicacion, e.nombre as 'editorial_nombre', c.nombre as 'categoria_nombre', c.pasillo, l.stoke 
            FROM libros l
            INNER JOIN autores a ON l.autor_id = a.id
            INNER JOIN editoriales e ON l.editorial_id = e.id
            INNER JOIN categorias c ON l.categoria_id = c.id
            WHERE l.titulo LIKE '%$buscar%' 
                OR l.isbn LIKE '%$buscar%'
                OR a.nombre LIKE '%$buscar%'
                OR e.nombre LIKE '%$buscar%'
                OR c.nombre LIKE '%$buscar%'
            ORDER BY l.titulo ASC";

    $mysql = $conexion->query($sql);

    if ($mysql->num_rows > 0) {

        while ($datos = $mysql->fetch_assoc()) {
            $json["libros"][] = $datos;
        }
        
    } else {
        $resultar = array(
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
        $json["libros"][] = $resultar;
    }

    $mysql->close();
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
}
