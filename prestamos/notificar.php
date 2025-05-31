<?php
require_once __DIR__ . "/../conexion.php";

$json = array();

if (isset($_GET["prestamo_id"])) {
    $prestamo_id = intval($_GET["prestamo_id"]);

    // Consulta para obtener los datos del préstamo, lector y libro
    $sql = "SELECT p.id,
                   p.lector_id, 
                   le.nombre AS lector_nombre, 
                   le.correo AS lector_correo,
                   li.titulo AS libro_titulo, 
                   p.fecha_prestamo, 
                   p.fecha_devolucion
            FROM prestamos p 
            INNER JOIN lectores le ON p.lector_id = le.id
            INNER JOIN libros li ON p.libro_id = li.id
            WHERE p.id = {$prestamo_id}";

    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        $registro = $result->fetch_assoc();

        // Todo lo relacionado para enviar el correo
        $saludo = "Hola {$registro['lector_nombre']}";
        $cuerpo_correo = "Queremos informarte que no has devuelto el libro <b>{$registro['libro_titulo']}</b>, lo solicitaste prestado en la fecha <b>{$registro['fecha_prestamo']}</b> y el cual debías devolver como máximo en la fecha <b>{$registro['fecha_devolucion']}</b>.<br> Como se te informó cuando fuiste registrado,</b> y hasta que devuelvas el o los libros no podrás solicitar más.<br><br> Por el momento es todo. <br><br>Sistema de Biblioteca Digital.";
        $mi_asunto = "Notificación de Retraso en Devolución";
        $correo = $registro["lector_correo"];
        require __DIR__ . "/enviar_correo.php"; // Archivo que maneja el envío de correos

        $id = $registro["lector_id"]; // Asegurar que el ID sea un número entero
        // Actualizar el estatus a 1
        $sql = "UPDATE lectores SET estatus = 0 WHERE id = {$id}";
        $mysql = $conexion->query($sql);

        if ($mysql) {

            $json = array(
                "status" => true,
                "message" => "Mensaje enviado con exito"
            );
        } else {
            $json = array(
                "status" => false,
                "message" => "Error al desactivar lector"
            );
        }
    } else {
        $json = array(
            "status" => false,
            "message" => "No encontrado"
        );
    }

    $conexion->close();
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
}
