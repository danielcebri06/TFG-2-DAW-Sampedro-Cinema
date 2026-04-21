<?php

namespace App\Controlador;

use App\Modelo\Conexion\BD;
use App\Modelo\DAO\PagoDAO;
use App\Modelo\Entidades\Pago;
use PDOException;

class ApiPagoController {

    private PagoDAO $pagoDAO;

    public function __construct() {
        $bd = BD::getConexion();
        $this->pagoDAO = new PagoDAO($bd);
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

    private function validarDatosPago(array $datos): bool {
        return isset(
            $datos['id_reserva'],
            $datos['importe'],
            $datos['estado'],
            $datos['fecha_hora'],
            $datos['stripe_payment_intent']
        );
    }

    public function listar(): void {
        try {
            $pagos = [];

            foreach ($this->pagoDAO->recuperaTodos() as $pago) {
                $pagos[] = [
                    'id_pago' => $pago->getId_pago(),
                    'id_reserva' => $pago->getId_reserva(),
                    'importe' => $pago->getImporte(),
                    'estado' => $pago->getEstado(),
                    'fecha_hora' => $pago->getFecha_hora(),
                    'stripe_payment_intent' => $pago->getStripe_payment_intent()
                ];
            }

            $this->enviarRespuesta($pagos);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al recuperar los pagos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function obtener(int $id_pago): void {
        try {
            $pago = $this->pagoDAO->recuperaPorId($id_pago);

            if (!$pago) {
                $this->enviarRespuesta([
                    'mensaje' => 'Pago no encontrado'
                ], 404);
            }

            $this->enviarRespuesta([
                'id_pago' => $pago->getId_pago(),
                'id_reserva' => $pago->getId_reserva(),
                'importe' => $pago->getImporte(),
                'estado' => $pago->getEstado(),
                'fecha_hora' => $pago->getFecha_hora(),
                'stripe_payment_intent' => $pago->getStripe_payment_intent()
            ]);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al recuperar el pago',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function crear(): void {
        try {
            $datos = $this->obtenerDatosJson();

            if (!$datos || !$this->validarDatosPago($datos)) {
                $this->enviarRespuesta([
                    'mensaje' => 'Datos de pago incompletos o inválidos'
                ], 400);
            }

            $pago = new Pago(
                null,
                (int) $datos['id_reserva'],
                (float) $datos['importe'],
                $datos['estado'],
                $datos['fecha_hora'],
                $datos['stripe_payment_intent']
            );

            $filas = $this->pagoDAO->crear($pago);

            $this->enviarRespuesta([
                'mensaje' => 'Pago creado correctamente',
                'filas_afectadas' => $filas
            ], 201);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al crear el pago',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function modificar(int $id_pago): void {
        try {
            $pagoExistente = $this->pagoDAO->recuperaPorId($id_pago);

            if (!$pagoExistente) {
                $this->enviarRespuesta([
                    'mensaje' => 'Pago no encontrado'
                ], 404);
            }

            $datos = $this->obtenerDatosJson();

            if (!$datos || !$this->validarDatosPago($datos)) {
                $this->enviarRespuesta([
                    'mensaje' => 'Datos de pago incompletos o inválidos'
                ], 400);
            }

            $pago = new Pago(
                $id_pago,
                (int) $datos['id_reserva'],
                (float) $datos['importe'],
                $datos['estado'],
                $datos['fecha_hora'],
                $datos['stripe_payment_intent']
            );

            $filas = $this->pagoDAO->modificar($pago);

            $this->enviarRespuesta([
                'mensaje' => 'Pago modificado correctamente',
                'filas_afectadas' => $filas
            ]);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al modificar el pago',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function eliminar(int $id_pago): void {
        try {
            $pagoExistente = $this->pagoDAO->recuperaPorId($id_pago);

            if (!$pagoExistente) {
                $this->enviarRespuesta([
                    'mensaje' => 'Pago no encontrado'
                ], 404);
            }

            $filas = $this->pagoDAO->eliminar($id_pago);

            $this->enviarRespuesta([
                'mensaje' => 'Pago eliminado correctamente',
                'filas_afectadas' => $filas
            ]);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al eliminar el pago',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function obtenerPorReserva(int $id_reserva): void {
        try {
            $pagos = [];

            foreach ($this->pagoDAO->recuperaPorReserva($id_reserva) as $pago) {
                $pagos[] = [
                    'id_pago' => $pago->getId_pago(),
                    'id_reserva' => $pago->getId_reserva(),
                    'importe' => $pago->getImporte(),
                    'estado' => $pago->getEstado(),
                    'fecha_hora' => $pago->getFecha_hora(),
                    'stripe_payment_intent' => $pago->getStripe_payment_intent()
                ];
            }

            $this->enviarRespuesta($pagos);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al recuperar los pagos de la reserva',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function actualizarEstado(int $id_pago): void {
        try {
            $pagoExistente = $this->pagoDAO->recuperaPorId($id_pago);

            if (!$pagoExistente) {
                $this->enviarRespuesta([
                    'mensaje' => 'Pago no encontrado'
                ], 404);
            }

            $datos = $this->obtenerDatosJson();

            if (!$datos || !isset($datos['estado'])) {
                $this->enviarRespuesta([
                    'mensaje' => 'Estado de pago no indicado'
                ], 400);
            }

            $filas = $this->pagoDAO->actualizarEstado($id_pago, $datos['estado']);

            $this->enviarRespuesta([
                'mensaje' => 'Estado del pago actualizado correctamente',
                'filas_afectadas' => $filas
            ]);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al actualizar el estado del pago',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

