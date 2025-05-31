<?php
require_once __DIR__ . "/../conexion.php";

$json = array();

if (
    isset($_GET["id"]) && isset($_GET["nombre"]) && isset($_GET["apellido"]) &&
    isset($_GET["telefono"]) && isset($_GET["correo"]) && isset($_GET["direccion"])
) {
    $id = intval($_GET["id"]); // Asegurar que sea un número
    $nombre = trim($_GET["nombre"]);
    $apellido = trim($_GET["apellido"]);
    $telefono = trim($_GET["telefono"]);
    $correo = trim($_GET["correo"]);
    $correo = filter_var($correo, FILTER_SANITIZE_EMAIL);
    $direccion = trim($_GET["direccion"]);

    // Verificar si el lector con ese ID existe
    $sql_check = "SELECT id FROM lectores WHERE id = {$id}";
    $result_check = $conexion->query($sql_check);

    if ($result_check->num_rows > 0) {
        // Actualizar el registro existente
        $sql_update = "UPDATE lectores SET 
                        nombre = {$nombre}, 
                        apellido = {$apellido}, 
                        telefono = {$telefono}, 
                        correo = '{$correo}', 
                        direccion = {$direccion} 
                        WHERE id = {$id}";

        if ($conexion->query($sql_update)) {
            $sql_select = "SELECT * FROM lectores WHERE id = {$id}";
            $result_select = $conexion->query($sql_select);

            if ($registro = $result_select->fetch_assoc()) {
                $json["lector"] = $registro;
            } else {
                $json["lector"] = array(
                    "id" => 0,
                    "nombre" => "No hay registro",
                    "apellido" => "No hay registro",
                    "telefono" => "No hay registro",
                    "correo" => "No hay registro",
                    "direccion" => "No hay registro"
                );
            }
        } else {
            $json["lector"] = array(
                "id" => 0,
                "nombre" => "No actualizado",
                "apellido" => "No actualizado",
                "telefono" => "No actualizado",
                "correo" => "No actualizado",
                "direccion" => "No actualizado"
            );
        }
    } else {
        $json["lector"] = array(
            "id" => 0,
            "nombre" => "No encontrado",
            "apellido" => "No encontrado",
            "telefono" => "No encontrado",
            "correo" => "No encontrado",
            "direccion" => "No encontrado"
        );
    }

    $conexion->close();

    echo json_encode($json, JSON_UNESCAPED_UNICODE);
}
