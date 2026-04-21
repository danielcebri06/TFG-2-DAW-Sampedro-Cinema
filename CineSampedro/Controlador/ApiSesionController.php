<?php

namespace App\Controlador;

use App\Modelo\Conexion\BD;
use App\Modelo\DAO\SesionDAO;
use App\Modelo\Entidades\Sesion;
use PDOException;

class ApiSesionController {

    private SesionDAO $sesionDAO;

    public function __construct() {
        $bd = BD::getConexion();
        $this->sesionDAO = new SesionDAO($bd);
    }
    
    private function enviarRespuesta(array $datos, int $codigo = 200): void {
        http_response_code($codigo);
        
        header('Content-Type: application/json; charset=utf-8');
        
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
        
        exit;
    }
    
    private function obtenerDatosJson(): ?array {
        $json = file_get_contents("php://input");
        
        $datos = json_decode($json, true);
        
        return is_array($datos) ? $datos : null;
    }
    
    private function validarDatosSesion(array $datos): bool {
        return isset(
                $datos['fecha_hora'],
                $datos['precio'],
                $datos['id_pelicula'],
                $datos['id_sala']
        );
    }
    
    public function listar(): void {
        try{
            $sesiones = [];
            
            foreach ($this->sesionDAO->recuperarTodos() as $sesion) {
                $sesiones[] = [
                    'id_sesion' => $sesion->getId_sesion(),
                    'fecha_hora' => $sesion->getFecha_hora(),
                    'precio' => $sesion->getPrecio(),
                    'id_pelicula' => $sesion->getId_pelicula(),
                    'id_sala' => $sesion->getId_sala()
                 ];
            }
            
            $this->enviarRespuesta($sesiones);
            
        } catch (PDOException $ex) {
            $this-> enviarRespuesta([
                'mensaje' =>'Error al recuperar las sesiones',
                'error' => $ex->getMessage()
            ], 500);
        }
    }
    
    public function obtener(int $id_sesion): void {
        try{
            $sesion = $this->sesionDAO->recuperarPorId($id_sesion);
            
            if (!$sesion) {
                $this->enviarRespuesta([
                    'mensaje' => 'Sesión no encontrada'
                ], 404);
            }
            
            $this->enviarRespuesta([
                'id_sesion' => $sesion->getId_sesion(),
                'fecha_hora' => $sesion->getFecha_hora(),
                'precio' => $sesion->getPrecio(),
                'id_pelicula' => $sesion->getId_pelicula(),
                'id_sala' => $sesion->getId_sala()
            ]);
        } catch (PDOException $ex) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al recuperar la sesión',
                'error' => $ex->getMessage()
            ], 500);
        }
    }
    
    public function crear(): void {
        try{
            $datos = $this->obtenerDatosJson();
            
            if (!$datos || !$this->validarDatosSesion($datos)) {
                $this->enviarRespuesta([
                    'mensaje' => 'Datos de sesión incompletos o inválidos'
                ],400);                
            }
            
            $sesion = new Sesion(
                    null, 
                    $datos['fecha_hora'],
                    (float) $datos['precio'],
                    (int) $datos ['id_pelicula'],
                    (int) $datos ['id_sala']
            );
            
            $filas = $this->sesionDAO->crear($sesion);
            
            $this->enviarRespuesta([
                'mensaje' => 'Sesión creada correctamente',
                'filas_afectadas' => $filas
            ], 201);
            
        } catch (PDOException $ex) {
            $this->enviarRespuesta( [
                'mensaje' => 'Error al crear la sesión',
                'erro' => $ex->getMessage()
            ], 500);
        }
    }
    
    public function modificar(int $id_sesion): void {
        try{
            $sesionExistente = $this->sesionDAO->recuperarPorId($id_sesion);
            
            if (!$sesionExistente) {
                $this->enviarRespuesta([
                    'mensaje' => 'Sesión no encotrada'
                ], 404);
            }
            
            $datos = $this->obtenerDatosJson();
            
            if (!$datos || !$this->validarDatosSesion($datos)) {
                $this->enviarRespuesta([
                    'mensaje' => 'Datos de sesión incompletos o inválidos'
                ], 400);
            }

            $sesion = new Sesion(
                $id_sesion,
                $datos['fecha_hora'],
                (float) $datos['precio'],
                (int) $datos['id_pelicula'],
                (int) $datos['id_sala']
            );

            $filas = $this->sesionDAO->modificar($sesion);

            $this->enviarRespuesta([
                'mensaje' => 'Sesión modificada correctamente',
                'filas_afectadas' => $filas
            ]);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al modificar la sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function eliminar(int $id_sesion): void {
        try {
            $sesionExistente = $this->sesionDAO->recuperaPorId($id_sesion);

            if (!$sesionExistente) {
                $this->enviarRespuesta([
                    'mensaje' => 'Sesión no encontrada'
                ], 404);
            }

            $filas = $this->sesionDAO->eliminar($id_sesion);

            $this->enviarRespuesta([
                'mensaje' => 'Sesión eliminada correctamente',
                'filas_afectadas' => $filas
            ]);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al eliminar la sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

