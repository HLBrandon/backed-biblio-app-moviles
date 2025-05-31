<?php
require_once __DIR__ . "/../conexion.php";

$json = array();

if (isset($_GET["isbn"], $_GET["lector_id"], $_GET["usuario_id"])) {

    $isbn = trim($_GET["isbn"]);
    $lector_id = trim($_GET["lector_id"]);
    $usuario_id = trim($_GET["usuario_id"]);

    // Verificar cuántos libros tiene actualmente prestados el lector con estatus activo
    $sqlPrestamosLector = "SELECT COUNT(*) as cantidad_prestamos FROM prestamos 
                           WHERE lector_id = '{$lector_id}' AND estatus = 1";
    $resultPrestamosLector = $conexion->query($sqlPrestamosLector);
    $cantidad_prestamos = $resultPrestamosLector->fetch_assoc()["cantidad_prestamos"];

    if ($cantidad_prestamos < 2) {
        // Obtener el ID y el stock del libro usando el ISBN
        $sqlLibro = "SELECT id, stoke FROM libros WHERE isbn = '{$isbn}'";
        $resultLibro = $conexion->query($sqlLibro);

        if ($resultLibro->num_rows > 0) {
            $libro = $resultLibro->fetch_assoc();
            $libro_id = $libro["id"];
            $stoke_actual = (int) $libro["stoke"];

            // Verificar que haya existencias antes de hacer el préstamo
            if ($stoke_actual > 0) {

                // Calcular fechas
                $fecha_prestamo = date("Y-m-d H:i:s"); // Fecha actual
                $fecha_devolucion = date("Y-m-d H:i:s", strtotime("+7 days")); // Fecha dentro de 7 días

                // Insertar el préstamo en la base de datos con estatus activo
                $sqlPrestamo = "INSERT INTO prestamos (lector_id, libro_id, usuario_id, fecha_prestamo, fecha_devolucion, estatus) 
                                VALUES ('{$lector_id}', '{$libro_id}', '{$usuario_id}', '{$fecha_prestamo}', '{$fecha_devolucion}', 1)";

                if ($conexion->query($sqlPrestamo) === TRUE) {

                    // Disminuir el stock en -1
                    $nuevo_stoke = $stoke_actual - 1;
                    $sqlActualizarStock = "UPDATE libros SET stoke = '{$nuevo_stoke}' WHERE id = '{$libro_id}'";
                    $conexion->query($sqlActualizarStock);

                    $json = array(
                        "status" => true,
                        "mensaje" => "Préstamo registrado exitosamente",
                        "prestamo" => array(
                            "lector_id" => $lector_id,
                            "libro_id" => $libro_id,
                            "usuario_id" => $usuario_id,
                            "fecha_prestamo" => $fecha_prestamo,
                            "fecha_devolucion" => $fecha_devolucion
                        )
                    );
                } else {
                    $json = array(
                        "status" => false,
                        "mensaje" => "Error al registrar el préstamo"
                    );
                }
            } else {
                $json = array(
                    "status" => false,
                    "mensaje" => "No hay existencias del libro disponible"
                );
            }
        } else {
            $json = array(
                "status" => false,
                "mensaje" => "Libro no encontrado"
            );
        }

        $resultLibro->close();
    } else {
        $json = array(
            "status" => false,
            "mensaje" => "El lector ya tiene el máximo permitido de préstamos activos (2 libros)"
        );
    }

    $resultPrestamosLector->close();
    $conexion->close();
    echo json_encode($json, JSON_UNESCAPED_UNICODE);

} else {
    echo json_encode(array("status" => false, "mensaje" => "Faltan parámetros"), JSON_UNESCAPED_UNICODE);
}