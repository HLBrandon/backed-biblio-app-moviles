<?php

require_once __DIR__ . "/../conexion.php";

$json = array();

$sql = "SELECT u.*, r.nombre AS 'role_nombre' FROM usuarios u
        INNER JOIN roles r ON u.role_id = r.id
        WHERE u.role_id != 1
        ORDER BY u.nombre ASC";

$mysql = $conexion->query($sql);

if ($mysql->num_rows > 0) {

    while ($datos = $mysql->fetch_assoc()) {
        $json["usuarios"][] = $datos;
    }

} else {
    $resultar = array(
        "id" => 0,
        "nombre" => "Sin registros",
        "apellido" => "Sin registros",
        "telefono" => "Sin registros",
        "usuario" => "Sin registros",
        "contrasenia" => "Sin registros",
        "role_id" => 0,
        "estatus" => 0,
        "role_nombre" => "Sin registro"
    );
    $json["usuarios"][] = $resultar;
}

$mysql->close();

echo json_encode($json, JSON_UNESCAPED_UNICODE);