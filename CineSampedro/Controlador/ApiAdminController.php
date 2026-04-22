<?php

namespace App\Controlador;

use App\Modelo\Conexion\BD;
use App\Modelo\DAO\PeliculaDAO;
use App\Modelo\DAO\SesionDAO;
use App\Modelo\DAO\SalaDAO;
use App\Modelo\DAO\ReservaDAO;
use App\Modelo\DAO\PagoDAO;
use App\Modelo\DAO\UsuarioDAO;
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
}

