<?php

namespace App\Controlador;

use App\Modelo\Conexion\BD;
use App\Modelo\DAO\PeliculaDAO;
use App\Modelo\Entidades\Pelicula;
use PDOException;

class ApiPeliculaController{
    private PeliculaDAO $peliculaDAO;
    
    public function __construct(){
        $bd = BD::getConexion();
        $this->peliculaDAO = new PeliculaDAO($bd);
    }
    
    private function enviarRespuesta(mixed $datos, int $codigo = 200): void{
        http_response_code($codigo);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    }
    
    private function obtenerDatosJson(): ?array {
        $json = file_get_contents("php://input");
        $datos = json_decode($json, true);
        
        return is_array($datos) ? $datos : null;
    }
    
    private function validarDatosPelicula(array $datos): bool{
        return isset(
                $datos['titulo'],
                $datos['sinopsis'],
                $datos['duracion_min'],
                $datos['imagen'],
                $datos['id_clasificacion']
        );
    }
    
    public function listar(): void {
        try{
            $peliculas = [];
            
            foreach ($this->peliculaDAO->recuperaTodos() as $pelicula){
                $peliculas[] = [
                    'id_pelicula' => $pelicula->getId_pelicula(),
                    'titulo' => $pelicula->getTitulo(),
                    'sinopsis' => $pelicula->getSinopsis(),
                    'duracion_min' => $pelicula->getDuracion_min(),
                    'imagen' => $pelicula->getImagen(),
                    'id_clasificacion' => $pelicula->getId_clasificacion()
                    
                ];
            }
            
            $this->enviarRespuesta($peliculas);
        } catch (Exception $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al recuperar las películas',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function obtener(int $id_pelicula): void{
        try{
            $pelicula = $this->peliculaDAO->recuperaPorId($id_pelicula);
            
            if(!$pelicula){
                $this->enviarRespuesta([
                    'mensaje' => 'Película no encotrada'
                ], 400);
            }
            
            $this->enviarRespuesta([
                'id_pelicula' => $pelicula->getId_pelicula(),
                'titulo' => $pelicula->getTitulo(),
                'sinopsis' => $pelicula->getSinopsis(),
                'duracion_min' => $pelicula->getDuracion_min(),
                'imagen' => $pelicula->getImagen(),
                'id_clasificacion' => $pelicula->getId_clasificacion()
            ]);
        } catch (Exception $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al recuperar las películas',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function crear(): void{
        try{
            $datos = $this->obtenerDatosJson();
            
            if(!$datos || !$this->validarDatosPelicula($datos)){
                $this->enviarRespuesta([
                    'mensaje' => 'Datos de película incompletos o inválidos'
                ], 400);
                
                return;
            }
            
            $pelicula = new Pelicula(
                    null,
                    $datos['titulo'],
                    $datos['sinopsis'],
                    (int) $datos['duracion_min'],
                    $datos['imagen'],
                    (int) $datos['id_clasificacion']
            );
            
            $filas = $this->peliculaDAO->crear($pelicula);
            
            $this->enviarRespuesta([
                'mensaje' => 'Película creada correctamente',
                'filas_afectadas' => $filas
            ], 201);
            
        } catch (Exception $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al crear la película',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function modificar(int $id_pelicula): void {
        try {
            $peliculaExistente = $this->peliculaDAO->recuperaPorId($id_pelicula);

            if (!$peliculaExistente) {
                $this->enviarRespuesta([
                    'mensaje' => 'Película no encontrada'
                ], 404);
                return;
            }

            $datos = $this->obtenerDatosJson();

            if (!$datos || !$this->validarDatosPelicula($datos)) {
                $this->enviarRespuesta([
                    'mensaje' => 'Datos de película incompletos o inválidos'
                ], 400);
                return;
            }

            $pelicula = new Pelicula(
                $id_pelicula,
                $datos['titulo'],
                $datos['sinopsis'],
                (int) $datos['duracion_min'],
                $datos['imagen'],
                (int) $datos['id_clasificacion']
            );  
            
            $filas = $this->peliculaDAO->modificar($pelicula);

            $this->enviarRespuesta([
                'mensaje' => 'Película modificada correctamente',
                'filas_afectadas' => $filas
            ]);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al modificar la película',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function eliminar(int $id_pelicula): void {
        try {
            $peliculaExistente = $this->peliculaDAO->recuperaPorId($id_pelicula);

            if (!$peliculaExistente) {
                $this->enviarRespuesta([
                    'mensaje' => 'Película no encontrada'
                ], 404);
                return;
            }

            $filas = $this->peliculaDAO->eliminar($id_pelicula);

            $this->enviarRespuesta([
                'mensaje' => 'Película eliminada correctamente',
                'filas_afectadas' => $filas
            ]);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al eliminar la película',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
