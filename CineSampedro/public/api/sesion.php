<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Controlador\ApiSesionController;

$controlador = new ApiSesionController();

$metodo = $_SERVER['REQUEST_METHOD'];

$id_sesion = filter_input(INPUT_GET, 'id_sesion', FILTER_VALIDATE_INT);

switch ($metodo){
    case 'GET':
        if ($id_sesion !== null && $id_sesion !== false){
            $controlador->obtener($id_sesion);
        } else {
            $controlador->listar();
        }
        break;
    
    case 'POST':
        $controlador->crear();
        break;
    
    case 'PUT':
        if($id_sesion !== null && $id_sesion !== false){
            $controlador->modificar($id_sesion);
        } else {
            http_response_code(400);
            
            header('Content-Type: application/json; charset=utf-8');
            
            echo json_encode([
                'mensaje' => 'Id de sesión obligatorio'
            ], JSON_UNESCAPED_UNICODE);
        }
        break;
        
    case 'DELETE':
        if ($id_sesion !== null && $id_sesion !== false) {
            $controlador->eliminar($id_sesion);            
        } else {
            http_response_code(400);
            
            header('Content-Type: application/json; charset=utf-8');
            
            echo json_encode([
                'mensaje' => 'Id de sesión obligatorio'
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

