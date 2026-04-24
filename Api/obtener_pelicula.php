<?php
// 1. CABECERAS CORS 
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit; }

// 2. RUTA CORRECTA AL VENDOR
require_once __DIR__ . '/../CineSampedro/vendor/autoload.php';

// 3. EJECUTAR
$idPelicula = null;

if (isset($_GET['id'])) {
    $idPelicula = (int) $_GET['id'];
} elseif (isset($_GET['id_pelicula'])) {
    $idPelicula = (int) $_GET['id_pelicula'];
}

if ($idPelicula) {
    $controlador = new \App\Controlador\ApiPeliculaController();
    $controlador->obtener($idPelicula);
} else {
    http_response_code(400);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'mensaje' => 'Parametro id o id_pelicula obligatorio'
    ], JSON_UNESCAPED_UNICODE);
}