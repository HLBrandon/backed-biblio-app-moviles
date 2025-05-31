<?php

require_once __DIR__ . "/../conexion.php";

$json = array();

$sql = "SELECT * FROM autores ORDER BY nombre ASC";
$mysql = $conexion->query($sql);

if ($mysql->num_rows > 0) {

    while ($datos = $mysql->fetch_assoc()) {
        $json["autores"][] = $datos;
    }

} else {
    $resultar = array(
        "id" => 0,
        "nombre" => "No hay registro"
    );
    $json["autores"][] = $resultar;
}

$mysql->close();

echo json_encode($json, JSON_UNESCAPED_UNICODE);