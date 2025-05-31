<?php

require_once __DIR__ . "/../conexion.php";

$json = array();

if (isset($_GET["id"])) {
    $id = intval($_GET["id"]); // Convertir el ID a entero para mayor seguridad

    $sql = "SELECT * FROM autores WHERE id = {$id}";
    $mysql = $conexion->query($sql);

    if ($mysql->num_rows > 0) {
        $json["autor"] = $mysql->fetch_assoc();
    } else {
        $json["autor"] = array(
            "id" => 0,
            "nombre" => "No hay registro"
        );
    }

    $mysql->close();
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
}