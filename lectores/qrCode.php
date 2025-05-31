<?php
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

require "../vendor/autoload.php";

// Crear el código QR
$qrCode = new QrCode($contenido);

$writer = new PngWriter();
$result = $writer->write($qrCode);

// Definir la ruta donde se guardará la imagen
$directory = '../image/';
$filename = $directory . $nombre_archivo;

// Asegurarse de que la carpeta "images/" exista
if (!is_dir($directory)) {
    mkdir($directory);
}

// Guardar el QR en la carpeta
$result->saveToFile($filename);