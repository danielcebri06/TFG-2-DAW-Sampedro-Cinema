<?php

namespace App\Controlador;

use App\Modelo\Conexion\BD;
use App\Modelo\DAO\ReservaDAO;
use App\Modelo\Entidades\Reserva;
use PDOException;

class ApiReservaController {

    private ReservaDAO $reservaDAO;

    public function __construct() {
        $bd = BD::getConexion();
        $this->reservaDAO = new ReservaDAO($bd);
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

    private function validarDatosReserva(array $datos): bool {
        return isset(
            $datos['id_usuario'],
            $datos['id_sesion'],
            $datos['fecha_hora'],
            $datos['estado']
        );
    }

    public function listar(): void {
        try {
            $reservas = [];

            foreach ($this->reservaDAO->recuperaTodos() as $reserva) {
                $reservas[] = [
                    'id_reserva' => $reserva->getId_reserva(),
                    'id_usuario' => $reserva->getId_usuario(),
                    'id_sesion' => $reserva->getId_sesion(),
                    'fecha_hora' => $reserva->getFecha_hora(),
                    'estado' => $reserva->getEstado()
                ];
            }

            $this->enviarRespuesta($reservas);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al recuperar las reservas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function obtener(int $id_reserva): void {
        try {
            $reserva = $this->reservaDAO->recuperaPorId($id_reserva);

            if (!$reserva) {
                $this->enviarRespuesta([
                    'mensaje' => 'Reserva no encontrada'
                ], 404);
            }

            $this->enviarRespuesta([
                'id_reserva' => $reserva->getId_reserva(),
                'id_usuario' => $reserva->getId_usuario(),
                'id_sesion' => $reserva->getId_sesion(),
                'fecha_hora' => $reserva->getFecha_hora(),
                'estado' => $reserva->getEstado()
            ]);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al recuperar la reserva',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function crear(): void {
        try {
            $datos = $this->obtenerDatosJson();

            if (!$datos || !$this->validarDatosReserva($datos)) {
                $this->enviarRespuesta([
                    'mensaje' => 'Datos de reserva incompletos o inválidos'
                ], 400);
            }

            $reserva = new Reserva(
                null,
                (int) $datos['id_usuario'],
                (int) $datos['id_sesion'],
                $datos['fecha_hora'],
                $datos['estado']
            );

            $filas = $this->reservaDAO->crear($reserva);

            $this->enviarRespuesta([
                'mensaje' => 'Reserva creada correctamente',
                'filas_afectadas' => $filas
            ], 201);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al crear la reserva',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function modificar(int $id_reserva): void {
        try {
            $reservaExistente = $this->reservaDAO->recuperaPorId($id_reserva);

            if (!$reservaExistente) {
                $this->enviarRespuesta([
                    'mensaje' => 'Reserva no encontrada'
                ], 404);
            }

            $datos = $this->obtenerDatosJson();

            if (!$datos || !$this->validarDatosReserva($datos)) {
                $this->enviarRespuesta([
                    'mensaje' => 'Datos de reserva incompletos o inválidos'
                ], 400);
            }

            $reserva = new Reserva(
                $id_reserva,
                (int) $datos['id_usuario'],
                (int) $datos['id_sesion'],
                $datos['fecha_hora'],
                $datos['estado']
            );

            $filas = $this->reservaDAO->modificar($reserva);

            $this->enviarRespuesta([
                'mensaje' => 'Reserva modificada correctamente',
                'filas_afectadas' => $filas
            ]);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al modificar la reserva',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function eliminar(int $id_reserva): void {
        try {
            $reservaExistente = $this->reservaDAO->recuperaPorId($id_reserva);

            if (!$reservaExistente) {
                $this->enviarRespuesta([
                    'mensaje' => 'Reserva no encontrada'
                ], 404);
            }

            $filas = $this->reservaDAO->eliminar($id_reserva);

            $this->enviarRespuesta([
                'mensaje' => 'Reserva eliminada correctamente',
                'filas_afectadas' => $filas
            ]);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al eliminar la reserva',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function obtenerPorUsuario(int $id_usuario): void {
        try {
            $reservas = [];

            foreach ($this->reservaDAO->recuperaPorUsuario($id_usuario) as $reserva) {
                $reservas[] = [
                    'id_reserva' => $reserva->getId_reserva(),
                    'id_usuario' => $reserva->getId_usuario(),
                    'id_sesion' => $reserva->getId_sesion(),
                    'fecha_hora' => $reserva->getFecha_hora(),
                    'estado' => $reserva->getEstado()
                ];
            }

            $this->enviarRespuesta($reservas);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al recuperar las reservas del usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function obtenerPorSesion(int $id_sesion): void {
        try {
            $reservas = [];

            foreach ($this->reservaDAO->recuperaPorSesion($id_sesion) as $reserva) {
                $reservas[] = [
                    'id_reserva' => $reserva->getId_reserva(),
                    'id_usuario' => $reserva->getId_usuario(),
                    'id_sesion' => $reserva->getId_sesion(),
                    'fecha_hora' => $reserva->getFecha_hora(),
                    'estado' => $reserva->getEstado()
                ];
            }

            $this->enviarRespuesta($reservas);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al recuperar las reservas de la sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
