<?php

require_once __DIR__ . "/../conexion.php";

$json = array();

if (
    isset($_GET["nombre"]) && isset($_GET["apellido"]) && isset($_GET["telefono"]) &&
    isset($_GET["usuario"]) && isset($_GET["contrasenia"]) && isset($_GET["role"])
) {

    $nombre = trim($_GET["nombre"]);
    $apellido = trim($_GET["apellido"]);
    $telefono = trim($_GET["telefono"]);
    $usuario = trim($_GET["usuario"]);
    $contrasenia = trim($_GET["contrasenia"]);
    $hash = password_hash($contrasenia, PASSWORD_DEFAULT);
    $roleNombre = trim($_GET["role"]);

    // Consultar el ID del role
    $sql = "SELECT id FROM roles WHERE nombre = {$roleNombre}";
    $mysql = $conexion->query($sql);
    $role_id = ($mysql->num_rows > 0) ? $mysql->fetch_assoc()["id"] : 0;

    // Verificar si se encontró el role antes de insertar
    if ($role_id > 0) {
        // Insertar el usuario en la tabla usuarios
        $sql = "INSERT INTO usuarios (nombre, apellido, telefono, usuario, contrasenia, role_id) 
                VALUES ({$nombre}, {$apellido}, {$telefono}, {$usuario}, '{$hash}', {$role_id})";

        $mysql = $conexion->query($sql);

        if ($mysql) {
            $id = $conexion->insert_id;

            // Obtener el usuario recién insertado
            $sql = "SELECT * FROM usuarios WHERE id = {$id}";
            $mysql = $conexion->query($sql);

            if ($registro = $mysql->fetch_assoc()) {
                // $json["usuario"] = $registro;

                $json = array(
                    "status" => true,
                    "mensaje" => "Usuario creado con exito",
                    "usuario" => $registro
                );
            } else {
                $json = array(
                    "status" => false,
                    "mensaje" => "Usuario no encontrado",
                    "usuario" => []
                );
            }
        } else {
            $json = array(
                "status" => false,
                "mensaje" => "Error en la inserción",
                "usuario" => []
            );
        }
    } else {
        $json = array(
            "status" => false,
            "mensaje" => "El role no existe",
            "usuario" => []
        );
    }

    $mysql->close();
    $conexion->close();

    echo json_encode($json, JSON_UNESCAPED_UNICODE);
}
