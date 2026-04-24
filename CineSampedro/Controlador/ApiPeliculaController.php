<?php

namespace App\Controlador;

use App\Modelo\Conexion\BD;
use App\Modelo\DAO\PeliculaDAO;
use App\Modelo\Entidades\Pelicula;
use PDOException;

class ApiPeliculaController {
    private PeliculaDAO $peliculaDAO;
    
    public function __construct() {
        $bd = BD::getConexion();
        $this->peliculaDAO = new PeliculaDAO($bd);
    }
    
    /**
     * Envía una respuesta JSON y detiene la ejecución.
     * Incluye cabeceras CORS para que Angular pueda conectarse.
     */
    private function enviarRespuesta(mixed $datos, int $codigo = 200): void {
        header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Content-Type: application/json; charset=utf-8');
        
        // Manejo de peticiones preflight OPTIONS de los navegadores
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);
            exit;
        }

        http_response_code($codigo);
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
        exit; // Detiene la ejecución para evitar salida extra
    }
    
    private function obtenerDatosJson(): ?array {
        $json = file_get_contents("php://input");
        $datos = json_decode($json, true);
        return is_array($datos) ? $datos : null;
    }
    
    private function validarDatosPelicula(array $datos): bool {
         $duracion = $datos['duracion_minutos'] ?? $datos['duracion_min'] ?? null;

        return !empty($datos['titulo']) &&
               !empty($datos['sinopsis']) &&
             $duracion !== null &&
               !empty($datos['imagen']) &&
               isset($datos['id_clasificacion']);
    }

    private function obtenerNombreClasificacion(int $idClasificacion): ?string {
        $bd = BD::getConexion();
        $sql = "SELECT nombre FROM clasificaciones_edad WHERE id_clasificacion = :id_clasificacion LIMIT 1";
        $resultado = $bd->prepare($sql);
        $resultado->execute([':id_clasificacion' => $idClasificacion]);
        $fila = $resultado->fetch(\PDO::FETCH_ASSOC);

        return $fila['nombre'] ?? null;
    }
    
    public function listar(): void {
        try {
            $peliculas = [];
            foreach ($this->peliculaDAO->recuperaTodos() as $pelicula) {
                $peliculas[] = [
                    'id_pelicula' => $pelicula->getId_pelicula(),
                    'titulo' => $pelicula->getTitulo(),
                    'sinopsis' => $pelicula->getSinopsis(),
                    'duracion_min' => $pelicula->getDuracion_min(),
                    'duracion_minutos' => $pelicula->getDuracion_minutos(),
                    'imagen' => $pelicula->getImagen(),
                    'fecha_estreno' => $pelicula->getFecha_estreno(),
                    'id_clasificacion' => $pelicula->getId_clasificacion()
                ];
            }
            $this->enviarRespuesta($peliculas);
        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al recuperar las películas',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function obtener(int $id_pelicula): void {
        try {
            $pelicula = $this->peliculaDAO->recuperaPorId($id_pelicula);
            
            if (!$pelicula) {
                $this->enviarRespuesta(['mensaje' => 'Película no encontrada'], 404);
            }

            $idClasificacion = $pelicula->getId_clasificacion();
            $nombreClasificacion = $this->obtenerNombreClasificacion($idClasificacion);
            
            $this->enviarRespuesta([
                'id_pelicula' => $pelicula->getId_pelicula(),
                'titulo' => $pelicula->getTitulo(),
                'sinopsis' => $pelicula->getSinopsis(),
                'duracion_min' => $pelicula->getDuracion_min(),
                'duracion_minutos' => $pelicula->getDuracion_minutos(),
                'imagen' => $pelicula->getImagen(),
                'fecha_estreno' => $pelicula->getFecha_estreno(),
                'id_clasificacion' => $idClasificacion,
                'clasificacion' => $nombreClasificacion
            ]);
        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al recuperar la película',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function crear(): void {
        try {
            $datos = $this->obtenerDatosJson();
            
            if (!$datos || !$this->validarDatosPelicula($datos)) {
                $this->enviarRespuesta(['mensaje' => 'Datos de película incompletos o inválidos'], 400);
            }

            $duracion = (int) ($datos['duracion_minutos'] ?? $datos['duracion_min']);
            $fechaEstreno = $datos['fecha_estreno'] ?? null;
            
            $pelicula = new Pelicula(
                null,
                $datos['titulo'],
                $datos['sinopsis'],
                $duracion,
                $datos['imagen'],
                $fechaEstreno,
                (int) $datos['id_clasificacion']
            );
            
            $filas = $this->peliculaDAO->crear($pelicula);
            $this->enviarRespuesta([
                'mensaje' => 'Película creada correctamente',
                'filas_afectadas' => $filas
            ], 201);
            
        } catch (PDOException $e) {
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
                $this->enviarRespuesta(['mensaje' => 'Película no encontrada'], 404);
            }

            $datos = $this->obtenerDatosJson();

            if (!$datos || !$this->validarDatosPelicula($datos)) {
                $this->enviarRespuesta(['mensaje' => 'Datos de película incompletos o inválidos'], 400);
            }

            $duracion = (int) ($datos['duracion_minutos'] ?? $datos['duracion_min']);
            $fechaEstreno = $datos['fecha_estreno'] ?? null;

            $pelicula = new Pelicula(
                $id_pelicula,
                $datos['titulo'],
                $datos['sinopsis'],
                $duracion,
                $datos['imagen'],
                $fechaEstreno,
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
                $this->enviarRespuesta(['mensaje' => 'Película no encontrada'], 404);
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