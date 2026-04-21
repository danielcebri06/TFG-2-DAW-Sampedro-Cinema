<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Controlador\ApiPagoController;

$controlador = new ApiPagoController();
$metodo = $_SERVER['REQUEST_METHOD'];

$id_pago = filter_input(INPUT_GET, 'id_pago', FILTER_VALIDATE_INT);
$id_reserva = filter_input(INPUT_GET, 'id_reserva', FILTER_VALIDATE_INT);
$accion = filter_input(INPUT_GET, 'accion');

switch ($metodo) {
    case 'GET':
        if ($id_pago) {
            $controlador->obtener($id_pago);
        } elseif ($id_reserva) {
            $controlador->obtenerPorReserva($id_reserva);
        } else {
            $controlador->listar();
        }
        break;

    case 'POST':
        $controlador->crear();
        break;

    case 'PUT':
        if ($id_pago && $accion === 'estado') {
            $controlador->actualizarEstado($id_pago);
        } elseif ($id_pago) {
            $controlador->modificar($id_pago);
        } else {
            http_response_code(400);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'mensaje' => 'Id de pago obligatorio'
            ], JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'DELETE':
        if ($id_pago) {
            $controlador->eliminar($id_pago);
        } else {
            http_response_code(400);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'mensaje' => 'Id de pago obligatorio'
            ], JSON_UNESCAPED_UNICODE);
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

