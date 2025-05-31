<?php
require_once __DIR__ . "/../conexion.php";

$json = array();

if (isset($_GET["id"])) {
    $prestamo_id = trim($_GET["id"]);

    // Verificar si el préstamo existe
    $sqlPrestamo = "SELECT id FROM prestamos WHERE id = '{$prestamo_id}'";
    $resultPrestamo = $conexion->query($sqlPrestamo);

    if ($resultPrestamo->num_rows > 0) {
        // Actualizar el estado del préstamo a "perdido"
        $sqlActualizarPrestamo = "UPDATE prestamos SET perder = 1 WHERE id = '{$prestamo_id}'";

        if ($conexion->query($sqlActualizarPrestamo) === TRUE) {
            $json = array(
                "status" => true,
                "mensaje" => "El libro ha sido marcado como perdido"
            );
        } else {
            $json = array(
                "status" => false,
                "mensaje" => "Error al actualizar el estado del préstamo"
            );
        }
    } else {
        $json = array(
            "status" => false,
            "mensaje" => "Préstamo no encontrado"
        );
    }

    $resultPrestamo->close();
    $conexion->close();
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(array("status" => false, "mensaje" => "Faltan parámetros"), JSON_UNESCAPED_UNICODE);
}
