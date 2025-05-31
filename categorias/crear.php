<?php

require_once __DIR__ . "/../conexion.php";

$json = array();

if (isset($_GET["nombre"]) && isset($_GET["pasillo"])) {

    $nombre = trim($_GET["nombre"]);
    $pasillo = $_GET["pasillo"];

    // Verificar si el nombre ya existe
    $sql_check = "SELECT id FROM categorias WHERE nombre = {$nombre}";
    $result_check = $conexion->query($sql_check);

    if ($result_check->num_rows > 0) {
        // Ya existe
        $json["categoria"] = array(
            "id" => 0,
            "nombre" => "Duplicado",
            "pasillo" => "Duplicado"
        );
    } else {
        // Insertar el nuevo registro
        $sql_insert = "INSERT INTO categorias (nombre, pasillo) VALUES ({$nombre}, {$pasillo})";
        if ($conexion->query($sql_insert)) {
            $id = $conexion->insert_id;

            $sql_select = "SELECT * FROM categorias WHERE id = {$id}";
            $result_select = $conexion->query($sql_select);

            if ($registro = $result_select->fetch_assoc()) {
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
                "id" => 0,
                "nombre" => "No insertado",
                "pasillo" => "No insertado"
            );
        }
    }

    $conexion->close();

    echo json_encode($json, JSON_UNESCAPED_UNICODE);
}