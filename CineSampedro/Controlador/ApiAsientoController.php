<?php
namespace App\Controlador;

use App\Modelo\Conexion\BD;
use App\Modelo\DAO\AsientoDAO;
use PDOException;

class ApiAsientoController {
    private AsientoDAO $asientoDAO;
    
    public function __construct() {
        $bd = BD::getConexion();
        $this->asientoDAO = new AsientoDAO($bd);
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

    public function obtenerMapaAsientos(int $id_sala, int $id_sesion): void {
        try {
            $asientosDB = $this->asientoDAO->recuperarEstadoAsientosPorSesion($id_sala, $id_sesion);
            
            // Construimos el ID visual (ej: "A1", "C4") para Angular
            $asientosFront = [];
            foreach ($asientosDB as $a) {
                $asientosFront[] = [
                    'id' => 'F' . $a['fila'] . 'N' . $a['numero'],
                    'id_real_db' => (int) $a['id_asiento'],
                    'fila' => (int) $a['fila'],
                    'numero' => (int) $a['numero'],
                    'tipo' => $a['tipo'],
                    'estado' => $a['estado']
                ];
            }

            $this->enviarRespuesta($asientosFront);
        } catch (PDOException $e) {
            $this->enviarRespuesta(['error' => $e->getMessage()], 500);
        }
    }
}