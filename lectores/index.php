<?php

require_once __DIR__ . "/../conexion.php";

$json = array();

$sql = "SELECT * FROM lectores ORDER BY nombre ASC";

$mysql = $conexion->query($sql);

if ($mysql->num_rows > 0) {

    while ($datos = $mysql->fetch_assoc()) {
        $json["lectores"][] = $datos;
    }

} else {
    $resultar = array(
        "id" => 0,
        "nombre" => "Sin registros",
        "apellido" => "Sin registros",
        "telefono" => "Sin registros",
        "direccion" => "Sin registros",
        "estatus" => 0,
    );
    $json["lectores"][] = $resultar;
}

$mysql->close();

echo json_encode($json, JSON_UNESCAPED_UNICODE);