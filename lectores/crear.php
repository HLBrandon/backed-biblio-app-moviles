<?php
require_once __DIR__ . "/../conexion.php";

$json = array();

if (
    isset($_GET["nombre"]) && isset($_GET["apellido"]) &&
    isset($_GET["telefono"]) && isset($_GET["correo"]) &&
    isset($_GET["direccion"])
) {
    $nombre = trim($_GET["nombre"]);
    $apellido = trim($_GET["apellido"]);
    $telefono = trim($_GET["telefono"]);
    $correo = trim($_GET["correo"]);
    $correo = filter_var($correo, FILTER_SANITIZE_EMAIL);
    $direccion = trim($_GET["direccion"]);

    // Verificar si el correo ya existe
    $sql_check = "SELECT id FROM lectores WHERE correo = '{$correo}'";
    $result_check = $conexion->query($sql_check);

    if ($result_check->num_rows > 0) {
        // Ya existe
        $json["lector"] = array(
            "id" => 0,
            "nombre" => "Duplicado",
            "apellidos" => "Duplicado",
            "telefono" => "Duplicado",
            "correo" => "Duplicado",
            "direccion" => "Duplicado"
        );
    } else {
        // Insertar el nuevo registro
        $sql_insert = "INSERT INTO lectores (nombre, apellido, telefono, correo, direccion) 
                       VALUES ({$nombre}, {$apellido}, {$telefono}, '{$correo}', {$direccion})";
        if ($conexion->query($sql_insert)) {
            $id = $conexion->insert_id;

            $sql_select = "SELECT * FROM lectores WHERE id = {$id}";
            $result_select = $conexion->query($sql_select);

            if ($registro = $result_select->fetch_assoc()) {

                // Datos para generar QR y enviar Correo
                $mi_asunto = "QR para prestamos y devoluciones";
                $saludo = "Hola {$nombre}!";
                $cuerpo_correo = "Este correo es por parte del sistema de la Biblioteca Digital. Te enviamos el QR que deberas presentar para solicitar el prestamo y devolución de tus libros. <br> Es importante que conserves el QR o el Número de Control para agilizar el proceso. <br><br> Te deseamos un excelente día <br><br><br><br>N° de Control: {$id}";
                $nombre_archivo = $id . ".png";
                $imagen = '../image/' . $nombre_archivo;
                $contenido = $id;

                require __DIR__ . "/qrCode.php";
                require __DIR__ . "/enviar_correo.php";

                // Verifica que el archivo existe antes de intentar eliminarlo
                if (file_exists($imagen)) {
                    unlink($imagen);
                }

                $json["lector"] = $registro;
            } else {
                $json["lector"] = array(
                    "id" => 0,
                    "nombre" => "No hay registro",
                    "apellidos" => "No hay registro",
                    "telefono" => "No hay registro",
                    "correo" => "No hay registro",
                    "direccion" => "No hay registro"
                );
            }
        } else {
            $json["lector"] = array(
                "id" => 0,
                "nombre" => "No insertado",
                "apellidos" => "No insertado",
                "telefono" => "No insertado",
                "correo" => "No insertado",
                "direccion" => "No insertado"
            );
        }
    }

    $conexion->close();

    echo json_encode($json, JSON_UNESCAPED_UNICODE);
}
