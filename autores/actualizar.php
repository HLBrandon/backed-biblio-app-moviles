<?php

require_once __DIR__ . "/../conexion.php";

$json = array();

if (isset($_GET["id"]) and isset($_GET["nombre"])) {

    $id = intval($_GET["id"]); 
    $nombre = trim($_GET["nombre"]);

    // Construcción de la consulta de actualización
    $sql = "UPDATE autores SET nombre = {$nombre} WHERE id = {$id}";
    $mysql = $conexion->query($sql);

    if ($mysql && $conexion->affected_rows > 0) {
        // Obtener los datos actualizados
        $sql = "SELECT * FROM autores WHERE id = {$id}";
        $mysql = $conexion->query($sql);

        if ($registro = $mysql->fetch_assoc()) {
            $json["autor"] = $registro;
        } else {
            $json["autor"] = array(
                "id" => 0,
                "nombre" => "No hay registro",
            );
        }
    } else {
        $json["autor"] = array(
            "id" => $id,
            "nombre" => "No actualizado"
        );
    }

    $mysql->close();
    $conexion->close();

    echo json_encode($json, JSON_UNESCAPED_UNICODE);
}