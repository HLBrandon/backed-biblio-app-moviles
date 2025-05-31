<?php

require_once __DIR__ . "/../conexion.php";

$json = array();

if (isset($_GET["id"])) {
    $id = intval($_GET["id"]); // Asegurar que el ID sea un número entero

    // Actualizar el estatus a 1
    $sql = "UPDATE usuarios SET estatus = 1 WHERE id = {$id}";
    $mysql = $conexion->query($sql);

    if ($mysql) {
        // Obtener el usuario actualizado
        $sql = "SELECT * FROM usuarios WHERE id = {$id}";
        $mysql = $conexion->query($sql);

        if ($registro = $mysql->fetch_assoc()) {
            $json["usuario"] = $registro;
        } else {
            $json["usuario"] = array(
                "id" => 0,
                "mensaje" => "No hay registro"
            );
        }
    } else {
        $json["usuario"] = array(
            "id" => 0,
            "mensaje" => "No actualizado"
        );
    }

    $mysql->close();
    $conexion->close();

    echo json_encode($json, JSON_UNESCAPED_UNICODE);
}