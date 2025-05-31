<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . "/config.php";

$conexion = new mysqli(HOST, USERNAME, PASS, DBNAME, PORT);

if ($conexion->connect_errno) {
    echo "Error de conexión: " . $conexion->connect_error;
    exit;
}