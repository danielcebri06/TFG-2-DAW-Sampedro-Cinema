<?php
// 1. CABECERAS CORS 
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

// Si es una petición de prueba de Angular, respondemos OK y salimos
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 2. RUTA CORRECTA AL VENDOR 
require_once __DIR__ . '/../CineSampedro/vendor/autoload.php';

// 3. EJECUTAR EL CONTROLADOR
if (isset($_GET['id_sala']) && isset($_GET['id_sesion'])) {
    $controlador = new \App\Controlador\ApiAsientoController();
    $controlador->obtenerMapaAsientos((int)$_GET['id_sala'], (int)$_GET['id_sesion']);
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Faltan parámetros: id_sala o id_sesion']);
}