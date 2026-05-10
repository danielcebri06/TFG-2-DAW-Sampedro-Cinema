<?php

namespace App\Controlador;

use App\Modelo\Conexion\BD;
use App\Modelo\DAO\PeliculaDAO;
use App\Modelo\DAO\SesionDAO;
use App\Modelo\DAO\SalaDAO;
use App\Modelo\DAO\ReservaDAO;
use App\Modelo\DAO\PagoDAO;
use App\Modelo\DAO\UsuarioDAO;
use App\Modelo\Entidades\Sala;
use PDOException;

class ApiAdminController {

    private PeliculaDAO $peliculaDAO;
    private SesionDAO $sesionDAO;
    private SalaDAO $salaDAO;
    private ReservaDAO $reservaDAO;
    private PagoDAO $pagoDAO;
    private UsuarioDAO $usuarioDAO;

    public function __construct() {
        $bd = BD::getConexion();
        $this->peliculaDAO = new PeliculaDAO($bd);
        $this->sesionDAO = new SesionDAO($bd);
        $this->salaDAO = new SalaDAO($bd);
        $this->reservaDAO = new ReservaDAO($bd);
        $this->pagoDAO = new PagoDAO($bd);
        $this->usuarioDAO = new UsuarioDAO($bd);
    }

    private function enviarRespuesta(array $datos, int $codigo = 200): void {
        http_response_code($codigo);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
        exit;
    }

    private function leerJson(): array {
        $json = file_get_contents('php://input');
        $datos = json_decode($json, true);

        if (!is_array($datos)) {
            $this->enviarRespuesta([
                'mensaje' => 'JSON no válido'
            ], 400);
        }

        return $datos;
    }

    public function resumen(): void {
        try {
            $peliculas = count(iterator_to_array($this->peliculaDAO->recuperaTodos()));
            $sesiones = count($this->sesionDAO->recuperarTodos());
            $salas = count($this->salaDAO->recuperarTodos());
            $reservas = count($this->reservaDAO->recuperaTodos());
            $pagos = count($this->pagoDAO->recuperaTodos());
            $usuarios = count($this->usuarioDAO->recuperaTodos());

            $this->enviarRespuesta([
                'peliculas' => $peliculas,
                'sesiones' => $sesiones,
                'salas' => $salas,
                'reservas' => $reservas,
                'pagos' => $pagos,
                'usuarios' => $usuarios
            ]);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al recuperar el resumen administrativo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function listarReservas(): void {
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

    public function listarPagos(): void {
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

    public function listarUsuarios(): void {
        try {
            $usuarios = [];

            foreach ($this->usuarioDAO->recuperaTodos() as $usuario) {
                $usuarios[] = [
                    'id_usuario' => $usuario->getId_usuario(),
                    'firebase_uid' => $usuario->getFirebase_uid(),
                    'nombre' => $usuario->getNombre(),
                    'apellidos' => $usuario->getApellidos(),
                    'email' => $usuario->getEmail(),
                    'rol' => $usuario->getRol()
                ];
            }

            $this->enviarRespuesta($usuarios);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al recuperar los usuarios',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function listarSalas(): void {
        try {
            $salas = [];

            foreach ($this->salaDAO->recuperarTodos() as $sala) {
                $salas[] = [
                    'id_sala' => $sala->getId_sala(),
                    'numero' => $sala->getNumero(),
                    'capacidad' => $sala->getCapacidad()
                ];
            }

            $this->enviarRespuesta($salas);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al recuperar las salas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function obtenerSala(int $id_sala): void {
        try {
            $sala = $this->salaDAO->recuperarPorId($id_sala);

            if ($sala === null) {
                $this->enviarRespuesta([
                    'mensaje' => 'Sala no encontrada'
                ], 404);
            }

            $this->enviarRespuesta([
                'id_sala' => $sala->getId_sala(),
                'numero' => $sala->getNumero(),
                'capacidad' => $sala->getCapacidad()
            ]);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al recuperar la sala',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function crearSala(): void {
        try {
            $datos = $this->leerJson();

            if (
                !isset($datos['numero']) ||
                !isset($datos['capacidad'])
            ) {
                $this->enviarRespuesta([
                    'mensaje' => 'Faltan datos obligatorios'
                ], 400);
            }

            if ((int)$datos['numero'] <= 0 || (int)$datos['capacidad'] <= 0) {
                $this->enviarRespuesta([
                    'mensaje' => 'El número de sala y la capacidad deben ser mayores que 0'
                ], 400);
            }

            $salaExistente = $this->salaDAO->recuperarPorNumero((int)$datos['numero']);

            if ($salaExistente !== null) {
                $this->enviarRespuesta([
                    'mensaje' => 'Ya existe una sala con ese número'
                ], 400);
            }

            $sala = new Sala(
                null,
                (int)$datos['numero'],
                (int)$datos['capacidad']
            );

            $this->salaDAO->crear($sala);

            $this->enviarRespuesta([
                'mensaje' => 'Sala creada correctamente'
            ], 201);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al crear la sala',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function modificarSala(int $id_sala): void {
        try {
            $salaExistente = $this->salaDAO->recuperarPorId($id_sala);

            if ($salaExistente === null) {
                $this->enviarRespuesta([
                    'mensaje' => 'Sala no encontrada'
                ], 404);
            }

            $datos = $this->leerJson();

            if (
                !isset($datos['numero']) ||
                !isset($datos['capacidad'])
            ) {
                $this->enviarRespuesta([
                    'mensaje' => 'Faltan datos obligatorios'
                ], 400);
            }

            if ((int)$datos['numero'] <= 0 || (int)$datos['capacidad'] <= 0) {
                $this->enviarRespuesta([
                    'mensaje' => 'El número de sala y la capacidad deben ser mayores que 0'
                ], 400);
            }

            $salaConEseNumero = $this->salaDAO->recuperarPorNumero((int)$datos['numero']);

            if (
                $salaConEseNumero !== null &&
                $salaConEseNumero->getId_sala() !== $id_sala
            ) {
                $this->enviarRespuesta([
                    'mensaje' => 'Ya existe otra sala con ese número'
                ], 400);
            }

            $sala = new Sala(
                $id_sala,
                (int)$datos['numero'],
                (int)$datos['capacidad']
            );

            $this->salaDAO->modificar($sala);

            $this->enviarRespuesta([
                'mensaje' => 'Sala modificada correctamente'
            ]);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al modificar la sala',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function eliminarSala(int $id_sala): void {
        try {
            $salaExistente = $this->salaDAO->recuperarPorId($id_sala);

            if ($salaExistente === null) {
                $this->enviarRespuesta([
                    'mensaje' => 'Sala no encontrada'
                ], 404);
            }

            $sesionesAsociadas = $this->sesionDAO->contarPorSala($id_sala);

            if ($sesionesAsociadas > 0) {
                $this->enviarRespuesta([
                    'mensaje' => 'No se puede eliminar la sala porque tiene sesiones asociadas'
                ], 400);
            }

            $this->salaDAO->eliminar($id_sala);

            $this->enviarRespuesta([
                'mensaje' => 'Sala eliminada correctamente'
            ]);

        } catch (PDOException $e) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al eliminar la sala. Puede que tenga sesiones asociadas.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

