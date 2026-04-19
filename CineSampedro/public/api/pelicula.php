<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Controlador\ApiPeliculaController;

$controlador = new ApiPeliculaController();
$metodo = $_SERVER['REQUEST_METHOD'];

$id_pelicula = filter_input(
    INPUT_GET,
    'id_pelicula',
    FILTER_VALIDATE_INT
);

switch ($metodo) {
    case 'GET':
        if ($id_pelicula) {
            $controlador->obtener($id_pelicula);
        } else {
            $controlador->listar();
        }
        break;

    case 'POST':
        $controlador->crear();
        break;

    case 'PUT':
        if ($id_pelicula) {
            $controlador->modificar($id_pelicula);
        } else {
            http_response_code(400);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'exito' => false,
                'mensaje' => 'Id de película obligatorio'
            ], JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'DELETE':
        if ($id_pelicula) {
            $controlador->eliminar($id_pelicula);
        } else {
            http_response_code(400);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'exito' => false,
                'mensaje' => 'Id de película obligatorio'
            ], JSON_UNESCAPED_UNICODE);
        }
        break;

    default:
        http_response_code(405);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'exito' => false,
            'mensaje' => 'Método no permitido'
        ], JSON_UNESCAPED_UNICODE);
        break;
}


