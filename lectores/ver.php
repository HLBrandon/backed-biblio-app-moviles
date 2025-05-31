<?php

require_once __DIR__ . "/../conexion.php";

$json = array();

if (isset($_GET["id"])) {

    $id = intval($_GET["id"]);

    $sql = "SELECT * FROM lectores WHERE id = {$id}";
    $mysql = $conexion->query($sql);

    if ($mysql->num_rows > 0) {
        $json["lector"] = $mysql->fetch_assoc();
    } else {
        $json["lector"] = array(
            "id" => 0,
            "nombre" => "No encontrado",
            "apellido" => "No encontrado",
            "telefono" => "No encontrado",
            "direccion" => "No encontrado",
            "estatus" => 0,
        );
    }

    $mysql->close();

    echo json_encode($json, JSON_UNESCAPED_UNICODE);
} 