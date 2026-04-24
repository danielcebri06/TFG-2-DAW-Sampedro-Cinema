<?php
namespace App\Controlador;

use App\Modelo\Conexion\BD;
use App\Modelo\DAO\SesionDAO;
use PDOException;

class ApiSesionController {
    private SesionDAO $sesionDAO;
    
    public function __construct() {
        $bd = BD::getConexion();
        $this->sesionDAO = new SesionDAO($bd);
    }
    
    private function enviarRespuesta(mixed $datos, int $codigo = 200): void {
        header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit; }

        http_response_code($codigo);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function obtenerPorPelicula(int $id_pelicula): void {
        try {
            $sesiones = $this->sesionDAO->recuperarPorPelicula($id_pelicula);
            
            // Formateamos las fechas y datos para que Angular los entienda fácil
            $resultado = array_map(function($s) {
                return [
                    'id' => $s['id'],
                    'fecha' => date('d/m/Y', strtotime($s['fecha_hora'])),
                    'hora' => date('H:i', strtotime($s['fecha_hora'])),
                    'formato' => 'Sala ' . $s['numero_sala'],
                    'precio' => (float)$s['precio'],
                    'id_sala' => $s['id_sala']
                ];
            }, $sesiones);

            $this->enviarRespuesta($resultado);
        } catch (PDOException $e) {
            $this->enviarRespuesta(['error' => $e->getMessage()], 500);
        }
    }
}