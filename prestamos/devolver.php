<?php
require_once __DIR__ . "/../conexion.php";

$json = array();

if (isset($_GET["id"])) {

    $prestamo_id = trim($_GET["id"]);

    // Obtener el libro_id y estatus del préstamo antes de actualizarlo
    $sqlPrestamo = "SELECT libro_id, estatus FROM prestamos WHERE id = '{$prestamo_id}'";
    $resultPrestamo = $conexion->query($sqlPrestamo);

    if ($resultPrestamo->num_rows > 0) {
        $prestamo = $resultPrestamo->fetch_assoc();
        $libro_id = $prestamo["libro_id"];
        $estatus = (int) $prestamo["estatus"];

        // Validar si el préstamo ya fue devuelto
        if ($estatus == 0) {
            $json = array(
                "status" => false,
                "mensaje" => "El préstamo ya fue devuelto anteriormente"
            );
        } else {
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

                // Actualizar el préstamo: marcar como devuelto (estatus = 0) y actualizar fecha de devolución
                $fecha_devolucion = date("Y-m-d H:i:s"); // Fecha actual de devolución
                $sqlActualizarPrestamo = "UPDATE prestamos SET estatus = 0, fecha_devolucion = '{$fecha_devolucion}' WHERE id = '{$prestamo_id}'";

                if ($conexion->query($sqlActualizarPrestamo) === TRUE) {
                    $json = array(
                        "status" => true,
                        "mensaje" => "Devolución registrada exitosamente",
                        "prestamo" => array(
                            "id" => $prestamo_id,
                            "libro_id" => $libro_id,
                            "fecha_devolucion" => $fecha_devolucion,
                            "estatus" => 0
                        )
                    );
                } else {
                    $json = array(
                        "status" => false,
                        "mensaje" => "Error al actualizar la devolución"
                    );
                }
            } else {
                $json = array(
                    "status" => false,
                    "mensaje" => "Libro no encontrado"
                );
            }
        }
    } else {
        $json = array(
            "status" => false,
            "mensaje" => "Préstamo no encontrado"
        );
    }

    $conexion->close();
    echo json_encode($json, JSON_UNESCAPED_UNICODE);

} else {
    echo json_encode(array("status" => false, "mensaje" => "Faltan parámetros"), JSON_UNESCAPED_UNICODE);
}