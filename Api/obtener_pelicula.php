<?php
// 1. CABECERAS CORS 
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit; }

// 2. RUTA CORRECTA AL VENDOR
require_once __DIR__ . '/../CineSampedro/vendor/autoload.php';

// 3. EJECUTAR
if (isset($_GET['id'])) {
    $controlador = new \App\Controlador\ApiPeliculaController();
    $controlador->obtener((int)$_GET['id']);
}