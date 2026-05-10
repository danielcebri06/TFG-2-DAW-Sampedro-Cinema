<?php

namespace App\Controlador;

use App\Modelo\Conexion\BD;
use App\Modelo\DAO\SesionDAO;
use App\Modelo\Entidades\Sesion;
use App\Modelo\DAO\PeliculaDAO;
use App\Modelo\DAO\SalaDAO;
use PDOException;

class ApiSesionController {

    private SesionDAO $sesionDAO;
    private PeliculaDAO $peliculaDAO;
    private SalaDAO $salaDAO;

    public function __construct() {
        $bd = BD::getConexion();
        $this->sesionDAO = new SesionDAO($bd);
        $this->peliculaDAO = new PeliculaDAO($bd);
        $this->salaDAO = new SalaDAO($bd);
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

    private function validarReglasSesion(array $datos, ?int $id_sesion = null): void {

        // Validación: el precio de la sesión debe ser mayor de 0.
        if ((float)$datos['precio'] <= 0) {
            $this->enviarRespuesta([
                'mensaje' => 'El precio de la sesión debe ser mayor que 0'
            ], 400);
        }

        // Validación: debe seleccionarse una película válida.
        if ((int)$datos['id_pelicula'] <= 0) {
            $this->enviarRespuesta([
                'mensaje' => 'Debes seleccionar una película válida'
            ], 400);
        }

        // Validación: debe seleccionarse una sala válida.
        if ((int)$datos['id_sala'] <= 0) {
            $this->enviarRespuesta([
                'mensaje' => 'Debes seleccionar una sala válida'
            ], 400);
        }

        // Validación: la película seleccionada debe existir en la base de datos.
        $pelicula = $this->peliculaDAO->recuperaPorId((int)$datos['id_pelicula']);

        if ($pelicula === null) {
            $this->enviarRespuesta([
                'mensaje' => 'La película seleccionada no existe'
            ], 400);
        }

        // Validación: la sala seleccionada debe existir en la base de datos.
        $sala = $this->salaDAO->recuperarPorId((int)$datos['id_sala']);

        if ($sala === null) {
            $this->enviarRespuesta([
                'mensaje' => 'La sala seleccionada no existe'
            ], 400);
        }

        /* Validación: no se permite duplicar una misma sesión
        con la misma película, sala y fecha/hora.*/
        $sesionDuplicada = $this->sesionDAO->recuperarSesionDuplicada(
            $datos['fecha_hora'],
            (int)$datos['id_pelicula'],
            (int)$datos['id_sala']
        );

        if (
            $sesionDuplicada !== null &&
            (
                $id_sesion === null ||
                $sesionDuplicada->getId_sesion() !== $id_sesion
            )
        ) {
            $this->enviarRespuesta([
                'mensaje' => 'Ya existe una sesión para esa película, sala y fecha/hora'
            ], 400);
        }

        /* Validación: una sala no puede tener dos sesiones
        empezando exactamente en la misma fecha y hora.*/
        $sesionMismaSalaYHora = $this->sesionDAO->recuperarSesionPorSalaYFecha(
            $datos['fecha_hora'],
            (int)$datos['id_sala']
        );

        if (
            $sesionMismaSalaYHora !== null &&
            (
                $id_sesion === null ||
                $sesionMismaSalaYHora->getId_sesion() !== $id_sesion
            )
        ) {
            $this->enviarRespuesta([
                'mensaje' => 'Ya existe una sesión en esa sala a esa fecha y hora'
            ], 400);
        }

        /* Validación: no se permite que una sesión se solape con otra en la misma sala. 
        Para calcularlo se tiene en cuenta:
            - hora de inicio de la nueva sesión
            - duración de la película
            - margen de limpieza de 20 minutos
            - sesiones existentes en esa misma sala.*/

        $margenLimpieza = 20;
        $inicioNuevaSesion = null;

        try {
            $inicioNuevaSesion = new \DateTime($datos['fecha_hora']);
        } catch (\Exception $e) {
            $this->enviarRespuesta([
                'mensaje' => 'La fecha y hora de la sesión no es válida'
            ], 400);
        }

        $duracionNuevaSesion = $pelicula->getDuracion_minutos() + $margenLimpieza;

        $finNuevaSesion = clone $inicioNuevaSesion;
        $finNuevaSesion->modify('+' . $duracionNuevaSesion . ' minutes');

        $sesionesMismaSala = $this->sesionDAO->recuperarSesionesPorSalaConDuracion(
            (int)$datos['id_sala']
        );

        foreach ($sesionesMismaSala as $sesionExistente) {

            // Si estamos editando una sesión, no se compara consigo misma.
            if (
                $id_sesion !== null &&
                (int)$sesionExistente['id_sesion'] === $id_sesion
            ) {
                continue;
            }

            $inicioSesionExistente = new \DateTime($sesionExistente['fecha_hora']);

            $duracionSesionExistente = 
                (int)$sesionExistente['duracion_minutos'] + $margenLimpieza;

            $finSesionExistente = clone $inicioSesionExistente;
            $finSesionExistente->modify('+' . $duracionSesionExistente . ' minutes');

            // Hay solapamiento si la nueva sesión empieza antes de que termine
            // la sesión existente y, además, termina después de que empiece la existente.
            if (
                $inicioNuevaSesion < $finSesionExistente &&
                $finNuevaSesion > $inicioSesionExistente
            ) {
                $this->enviarRespuesta([
                    'mensaje' => 'La sala ya tiene una sesión que se solapa con ese horario'
                ], 400);
            }
        }
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

            $this->validarReglasSesion($datos);
            
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
                'error' => $ex->getMessage()
            ], 500);
        }
    }
    
    public function modificar(int $id_sesion): void {
        try{
            $sesionExistente = $this->sesionDAO->recuperarPorId($id_sesion);
            
            if (!$sesionExistente) {
                $this->enviarRespuesta([
                    'mensaje' => 'Sesión no encontrada'
                ], 404);
            }
            
            $datos = $this->obtenerDatosJson();
            
            if (!$datos || !$this->validarDatosSesion($datos)) {
                $this->enviarRespuesta([
                    'mensaje' => 'Datos de sesión incompletos o inválidos'
                ], 400);
            }

            $this->validarReglasSesion($datos, $id_sesion);

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
            $sesionExistente = $this->sesionDAO->recuperarPorId($id_sesion);

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

    public function obtenerPorPelicula(int $id_pelicula): void {
        try {
            $sesiones = [];

            foreach ($this->sesionDAO->recuperarPorPelicula($id_pelicula) as $sesion) {
                $sesiones[] = [
                    'id' => (int) $sesion['id_sesion'],
                    'id_sesion' => (int) $sesion['id_sesion'],
                    'fecha_hora' => $sesion['fecha_hora'],
                    'fecha' => date('d/m/Y', strtotime($sesion['fecha_hora'])),
                    'hora' => date('H:i', strtotime($sesion['fecha_hora'])),
                    'precio' => (float) $sesion['precio'],
                    'id_pelicula' => (int) $sesion['id_pelicula'],
                    'id_sala' => (int) $sesion['id_sala'],
                    'formato' => 'Sala ' . $sesion['numero_sala']
                ];
            }

            $this->enviarRespuesta($sesiones);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al recuperar las sesiones de la película',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}