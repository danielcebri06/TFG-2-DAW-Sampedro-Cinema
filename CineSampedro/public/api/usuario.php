<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Controlador\ApiUsuarioController;

$controlador = new ApiUsuarioController();
$metodo = $_SERVER['REQUEST_METHOD'];

$id_usuario = filter_input(INPUT_GET, 'id_usuario', FILTER_VALIDATE_INT);
$firebase_uid = filter_input(INPUT_GET, 'firebase_uid');
$email = filter_input(INPUT_GET, 'email');

switch ($metodo) {
    case 'GET':
        if ($id_usuario) {
            $controlador->obtener($id_usuario);
        } elseif ($firebase_uid) {
            $controlador->obtenerPorFirebaseUid($firebase_uid);
        } elseif ($email) {
            $controlador->obtenerPorEmail($email);
        } else {
            $controlador->listar();
        }
        break;

    case 'POST':
        $controlador->crear();
        break;

    case 'PUT':
        if ($id_usuario) {
            $controlador->modificar($id_usuario);
        } else {
            http_response_code(400);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'mensaje' => 'Id de usuario obligatorio'
            ], JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'DELETE':
        if ($id_usuario) {
            $controlador->eliminar($id_usuario);
        } else {
            http_response_code(400);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'mensaje' => 'Id de usuario obligatorio'
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
