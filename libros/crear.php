<?php

require_once __DIR__ . "/../conexion.php";

$json = array();

if (isset($_GET["isbn"]) and isset($_GET["titulo"]) and isset($_GET["autor"]) and isset($_GET["edit"]) and isset($_GET["anio"]) and isset($_GET["cat"]) and isset($_GET["stoke"])) {

    $isbn = trim($_GET["isbn"]);
    $titulo = trim($_GET["titulo"]);
    $autorNombre = trim($_GET["autor"]);
    $editorialNombre = trim($_GET["edit"]);
    $anioPublicacion = trim($_GET["anio"]);
    $categoriaNombre = trim($_GET["cat"]);
    $stoke = trim($_GET["stoke"]);

    // Consultar el ID del autor
    $sql = "SELECT id FROM autores WHERE nombre = {$autorNombre}";
    $mysql = $conexion->query($sql);
    $autor_id = ($mysql->num_rows > 0) ? $mysql->fetch_assoc()["id"] : 0;

    // Consultar el ID de la editorial
    $sql = "SELECT id FROM editoriales WHERE nombre = {$editorialNombre}";
    $mysql = $conexion->query($sql);
    $editorial_id = ($mysql->num_rows > 0) ? $mysql->fetch_assoc()["id"] : 0;

    // Consultar el ID de la categoría
    $sql = "SELECT id FROM categorias WHERE nombre = {$categoriaNombre}";
    $mysql = $conexion->query($sql);
    $categoria_id = ($mysql->num_rows > 0) ? $mysql->fetch_assoc()["id"] : 0;

    // Verificar que se encontraron los IDs antes de insertar
    if ($autor_id > 0 && $editorial_id > 0 && $categoria_id > 0) {
        // Insertar el libro en la tabla libros
        $sql = "INSERT INTO libros (isbn, titulo, autor_id, anio_publicacion, editorial_id, categoria_id, stoke) 
                VALUES ({$isbn}, {$titulo}, {$autor_id}, {$anioPublicacion}, {$editorial_id}, {$categoria_id}, {$stoke})";
        
        $mysql = $conexion->query($sql);

        if ($mysql) {
            $id = $conexion->insert_id;
            
            // Obtener el libro recién insertado
            $sql = "SELECT * FROM libros WHERE id = {$id}";
            $mysql = $conexion->query($sql);

            if ($registro = $mysql->fetch_assoc()) {
                $json["libro"] = $registro;
            } else {
                $json["libro"] = array(
                    "id" => 0,
                    "mensaje" => "No hay registro"
                );
            }
        } else {
            $json["libro"] = array(
                "id" => 0,
                "mensaje" => "No insertado"
            );
        }
    } else {
        $json["libro"] = array(
            "id" => 0,
            "mensaje" => "No se encontraron los registros de autor, editorial o categoría"
        );
    }

    $mysql->close();
    $conexion->close();

    echo json_encode($json, JSON_UNESCAPED_UNICODE);
}