<?php

require_once __DIR__ . "/../conexion.php";

$json = array();

if (isset($_GET["id"])) {

    $id = intval($_GET["id"]); // Convertir el ID a entero para mayor seguridad

    $sql = "SELECT u.id, u.nombre, u.apellido, u.telefono, u.usuario, u.estatus, r.nombre AS 'role_nombre' FROM usuarios u
        INNER JOIN roles r ON u.role_id = r.id
        WHERE u.id = {$id}
        ORDER BY u.nombre ASC";

    $mysql = $conexion->query($sql);

    if ($mysql->num_rows > 0) {

        $json["usuario"] = $mysql->fetch_assoc();

    } else {

        $json["usuario"] = array(
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

    }

    $mysql->close();

    echo json_encode($json, JSON_UNESCAPED_UNICODE);

}
