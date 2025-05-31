<?php

require_once __DIR__ . "/../conexion.php";

$json = array();

if (isset($_GET["id"]) and isset($_GET["nombre"]) and isset($_GET["pasillo"])) {

    $id = intval($_GET["id"]); 
    $nombre = trim($_GET["nombre"]);
    $pasillo = intval($_GET["pasillo"]);

    // Construcción de la consulta de actualización
    $sql = "UPDATE categorias SET nombre = {$nombre}, pasillo = {$pasillo} WHERE id = {$id}";
    $mysql = $conexion->query($sql);

    if ($mysql && $conexion->affected_rows > 0) {
        // Obtener los datos actualizados
        $sql = "SELECT * FROM categorias WHERE id = {$id}";
        $mysql = $conexion->query($sql);

        if ($registro = $mysql->fetch_assoc()) {
            $json["categoria"] = $registro;
        } else {
            $json["categoria"] = array(
                "id" => 0,
                "nombre" => "No hay registro",
                "pasillo" => "No hay registro"
            );
        }
    } else {
        $json["categoria"] = array(
            "id" => $id,
            "nombre" => "No actualizado",
            "pasillo" => "No actualizado"
        );
    }

    $mysql->close();
    $conexion->close();

    echo json_encode($json, JSON_UNESCAPED_UNICODE);
}