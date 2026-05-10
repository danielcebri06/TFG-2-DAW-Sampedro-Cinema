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

        /* Validación inicial: comprueba que los campos necesarios han llegado en el JSON.*/
        return isset(
            $datos['titulo'],
            $datos['sinopsis'],
            $datos['imagen'],
            $datos['fecha_estreno'],
            $datos['id_clasificacion']
        ) && $duracion !== null;
    }

    private function validarReglasPelicula(array $datos): void {
        $duracion = (int) ($datos['duracion_minutos'] ?? $datos['duracion_min']);

        /*Validación: el título es obligatorio. Evita registrar películas sin nombre o con 
        espacios en blanco.*/
        if (trim($datos['titulo']) === '') {
            $this->enviarRespuesta([
                'mensaje' => 'El título de la película es obligatorio'
            ], 400);
        }

        /*
        * Validación: la sinopsis es obligatoria. La ficha de una película debe tener 
        una descripción mínima.*/
        if (trim($datos['sinopsis']) === '') {
            $this->enviarRespuesta([
                'mensaje' => 'La sinopsis de la película es obligatoria'
            ], 400);
        }

        /*Validación: la duración debe ser mayor que 0. No tendría sentido guardar una película 
        con duración 0 o negativa.*/
        if ($duracion <= 0) {
            $this->enviarRespuesta([
                'mensaje' => 'La duración de la película debe ser mayor que 0'
            ], 400);
        }

        /*Validación: la imagen o ruta del cartel es obligatoria. La cartelera necesita 
        una imagen para mostrar la película correctamente.*/
        if (trim($datos['imagen']) === '') {
            $this->enviarRespuesta([
                'mensaje' => 'La imagen de la película es obligatoria'
            ], 400);
        }

        /*Validación: la fecha de estreno es obligatoria. Se necesita para mostrar correctamente
        la película en cartelera o próximos estrenos.*/
        if (trim($datos['fecha_estreno']) === '') {
            $this->enviarRespuesta([
                'mensaje' => 'La fecha de estreno es obligatoria'
            ], 400);
        }

        /*Validación: debe seleccionarse una clasificación válida. El id de clasificación 
        debe ser mayor que 0.*/
        if ((int)$datos['id_clasificacion'] <= 0) {
            $this->enviarRespuesta([
                'mensaje' => 'Debes seleccionar una clasificación válida'
            ], 400);
        }

        /*Validación: la clasificación debe existir en la base de datos.
        No basta con recibir un id; ese id debe corresponder a una clasificación real.*/
        $nombreClasificacion = $this->obtenerNombreClasificacion((int)$datos['id_clasificacion']);

        if ($nombreClasificacion === null) {
            $this->enviarRespuesta([
                'mensaje' => 'La clasificación seleccionada no existe'
            ], 400);
        }
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

            $this->validarReglasPelicula($datos);

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

            $this->validarReglasPelicula($datos);

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