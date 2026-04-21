<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Controlador\ApiReservaController;

$controlador = new ApiReservaController();
$metodo = $_SERVER['REQUEST_METHOD'];

$id_reserva = filter_input(INPUT_GET, 'id_reserva', FILTER_VALIDATE_INT);
$id_usuario = filter_input(INPUT_GET, 'id_usuario', FILTER_VALIDATE_INT);
$id_sesion = filter_input(INPUT_GET, 'id_sesion', FILTER_VALIDATE_INT);

switch ($metodo) {
    case 'GET':
        if ($id_reserva) {
            $controlador->obtener($id_reserva);
        } elseif ($id_usuario) {
            $controlador->obtenerPorUsuario($id_usuario);
        } elseif ($id_sesion) {
            $controlador->obtenerPorSesion($id_sesion);
        } else {
            $controlador->listar();
        }
        break;

    case 'POST':
        $controlador->crear();
        break;

    case 'PUT':
        if ($id_reserva) {
            $controlador->modificar($id_reserva);
        } else {
            http_response_code(400);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'mensaje' => 'Id de reserva obligatorio'
            ], JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'DELETE':
        if ($id_reserva) {
            $controlador->eliminar($id_reserva);
        } else {
            http_response_code(400);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'mensaje' => 'Id de reserva obligatorio'
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
