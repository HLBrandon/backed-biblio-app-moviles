<?php

require_once __DIR__ . "/../conexion.php";

$json = array();

if (
    isset($_GET["nombre"]) && isset($_GET["apellido"]) && isset($_GET["telefono"]) &&
    isset($_GET["usuario"]) && isset($_GET["role"]) && isset($_GET["id"])
) {

    $id = trim($_GET["id"]);
    $nombre = trim($_GET["nombre"]);
    $apellido = trim($_GET["apellido"]);
    $telefono = trim($_GET["telefono"]);
    $usuario = trim($_GET["usuario"]);
    $roleNombre = trim($_GET["role"]);

    // Consultar el ID del role
    $sql = "SELECT id FROM roles WHERE nombre = {$roleNombre}";
    $mysql = $conexion->query($sql);
    $role_id = ($mysql->num_rows > 0) ? $mysql->fetch_assoc()["id"] : 0;

    // Verificar si se encontró el role antes de actualizar
    if ($role_id > 0) {
        // Construir la consulta de actualización
        $sql = "UPDATE usuarios SET nombre = {$nombre}, apellido = {$apellido}, telefono = {$telefono}, role_id = {$role_id}";

        // Si la contraseña está presente, se actualiza
        if (isset($_GET["contrasenia"]) && !empty($_GET["contrasenia"])) {
            $contrasenia = trim($_GET["contrasenia"]);
            $hash = password_hash($contrasenia, PASSWORD_DEFAULT);
            $sql .= ", contrasenia = '{$hash}'";
        }

        // Agregar condición de usuario
        $sql .= " WHERE id = {$id}";

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
    } else {
        $json["usuario"] = array(
            "id" => 0,
            "mensaje" => "No se encontró el role especificado"
        );
    }

    $mysql->close();
    $conexion->close();

    echo json_encode($json, JSON_UNESCAPED_UNICODE);
}
