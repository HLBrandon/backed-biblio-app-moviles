<?php
require_once __DIR__ . "/../conexion.php";

$json = array();

if (isset($_GET["username"], $_GET["pass"])) {

    $username = trim($_GET["username"]);
    $pass = trim($_GET["pass"]);

    $sql = "SELECT * FROM usuarios WHERE usuario = '{$username}'";

    $mysql = $conexion->query($sql);

    if ($mysql->num_rows > 0) {
        $user = $mysql->fetch_assoc();

        // Verificar contraseña con password_verify()
        if (password_verify($pass, $user["contrasenia"])) {
            // Validar estatus
            if ($user["estatus"] == 1) {
                $json = array(
                    "status" => true,
                    "mensaje" => "Bienvenido...",
                    "usuario" => array(
                        "id" => $user["id"],
                        "role_id" => $user["role_id"]
                    )
                );
            } else {
                $json = array(
                    "status" => false,
                    "mensaje" => "La cuenta está desactivada.",
                    "usuario" => []
                );
            }
        } else {
            $json = array(
                "status" => false,
                "mensaje" => "Usuario o contraseña incorrectos.",
                "usuario" => []
            );
        }
    } else {
        $json = array(
            "status" => false,
            "mensaje" => "Usuario o contraseña incorrectos.",
            "usuario" => []
        );
    }

    $mysql->close();
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
}