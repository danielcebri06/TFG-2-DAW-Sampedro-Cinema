<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Controlador\ApiAdminController;

$controlador = new ApiAdminController();
$metodo = $_SERVER['REQUEST_METHOD'];
$accion = filter_input(INPUT_GET, 'accion');

switch ($metodo) {
    case 'GET':
        switch ($accion) {
            case 'resumen':
                $controlador->resumen();
                break;

            case 'reservas':
                $controlador->listarReservas();
                break;

            case 'pagos':
                $controlador->listarPagos();
                break;

            case 'usuarios':
                $controlador->listarUsuarios();
                break;

            case 'salas':
                $controlador->listarSalas();
                break;

            default:
                http_response_code(400);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'mensaje' => 'Acción administrativa no válida'
                ], JSON_UNESCAPED_UNICODE);
                break;
        }
        break;

    default:
        http_response_code(405);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'mensaje' => 'Método no permitido'
        ], JSON_UNESCAPED_UNICODE);
        break;
}

