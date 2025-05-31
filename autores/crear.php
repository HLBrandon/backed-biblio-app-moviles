<?php

require_once __DIR__ . "/../conexion.php";

$json = array();

if (isset($_GET["nombre"])) {

    $nombre = trim($_GET["nombre"]);

    // Verificar si el nombre ya existe
    $sql_check = "SELECT id FROM autores WHERE nombre = {$nombre}";
    $result_check = $conexion->query($sql_check);

    if ($result_check->num_rows > 0) {
        // Ya existe
        $json["autor"] = array(
            "id" => 0,
            "nombre" => "Duplicado",
        );
    } else {
        // Insertar el nuevo registro
        $sql_insert = "INSERT INTO autores (nombre) VALUES ({$nombre})";
        if ($conexion->query($sql_insert)) {
            $id = $conexion->insert_id;

            $sql_select = "SELECT * FROM autores WHERE id = {$id}";
            $result_select = $conexion->query($sql_select);

            if ($registro = $result_select->fetch_assoc()) {
                $json["autor"] = $registro;
            } else {
                $json["autor"] = array(
                    "id" => 0,
                    "nombre" => "No hay registro"
                );
            }
        } else {
            $json["autor"] = array(
                "id" => 0,
                "nombre" => "No insertado"
            );
        }
    }

    $conexion->close();

    echo json_encode($json, JSON_UNESCAPED_UNICODE);
}