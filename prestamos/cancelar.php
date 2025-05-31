<?php
require_once __DIR__ . "/../conexion.php";

$json = array();

if (isset($_GET["id"])) {

    $prestamo_id = trim($_GET["id"]);

    // Obtener el libro_id asociado al préstamo antes de eliminarlo
    $sqlPrestamo = "SELECT libro_id FROM prestamos WHERE id = '{$prestamo_id}'";
    $resultPrestamo = $conexion->query($sqlPrestamo);

    if ($resultPrestamo->num_rows > 0) {
        $prestamo = $resultPrestamo->fetch_assoc();
        $libro_id = $prestamo["libro_id"];

        // Obtener el stock actual del libro
        $sqlLibro = "SELECT stoke FROM libros WHERE id = '{$libro_id}'";
        $resultLibro = $conexion->query($sqlLibro);

        if ($resultLibro->num_rows > 0) {
            $libro = $resultLibro->fetch_assoc();
            $stoke_actual = (int) $libro["stoke"];

            // Incrementar el stock en +1
            $nuevo_stoke = $stoke_actual + 1;
            $sqlActualizarStock = "UPDATE libros SET stoke = '{$nuevo_stoke}' WHERE id = '{$libro_id}'";
            $conexion->query($sqlActualizarStock);

            // Eliminar el préstamo
            $sqlEliminarPrestamo = "DELETE FROM prestamos WHERE id = '{$prestamo_id}'";
            if ($conexion->query($sqlEliminarPrestamo) === TRUE) {
                $json = array(
                    "status" => true,
                    "mensaje" => "Préstamo cancelado exitosamente"
                );
            } else {
                $json = array(
                    "status" => false,
                    "mensaje" => "Error al eliminar el préstamo"
                );
            }
        } else {
            $json = array(
                "status" => false,
                "mensaje" => "Libro no encontrado"
            );
        }
    } else {
        $json = array(
            "status" => false,
            "mensaje" => "Préstamo no encontrado"
        );
    }

    $resultPrestamo->close();
    $resultLibro->close();
    $conexion->close();
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(array("status" => false, "mensaje" => "Faltan parámetros"), JSON_UNESCAPED_UNICODE);
}
