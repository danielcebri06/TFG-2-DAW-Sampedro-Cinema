<?php

namespace App\Controlador;

use App\Modelo\Conexion\BD;
use App\Modelo\DAO\UsuarioDAO;
use App\Modelo\Entidades\Usuario;
use PDOException;

class ApiUsuarioController {

    private UsuarioDAO $usuarioDAO;

    public function __construct() {
        $bd = BD::getConexion();
        $this->usuarioDAO = new UsuarioDAO($bd);
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

    private function validarDatosUsuario(array $datos): bool {
        return isset(
            $datos['firebase_uid'],
            $datos['nombre'],
            $datos['apellidos'],
            $datos['email'],
            $datos['rol']
        );
    }

    public function listar(): void {
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

        } catch (PDOException $ex) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al recuperar los usuarios',
                'error' => $ex->getMessage()
            ], 500);
        }
    }

    public function obtener(int $id_usuario): void {
        try {
            $usuario = $this->usuarioDAO->recuperaPorId($id_usuario);

            if (!$usuario) {
                $this->enviarRespuesta([
                    'mensaje' => 'Usuario no encontrado'
                ], 404);
            }

            $this->enviarRespuesta([
                'id_usuario' => $usuario->getId_usuario(),
                'firebase_uid' => $usuario->getFirebase_uid(),
                'nombre' => $usuario->getNombre(),
                'apellidos' => $usuario->getApellidos(),
                'email' => $usuario->getEmail(),
                'rol' => $usuario->getRol()
            ]);

        } catch (PDOException $ex) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al recuperar el usuario',
                'error' => $ex->getMessage()
            ], 500);
        }
    }

    public function crear(): void {
        try {
            $datos = $this->obtenerDatosJson();

            if (!$datos || !$this->validarDatosUsuario($datos)) {
                $this->enviarRespuesta([
                    'mensaje' => 'Datos de usuario incompletos o inválidos'
                ], 400);
            }

            $usuario = new Usuario(
                null,
                $datos['firebase_uid'],
                $datos['nombre'],
                $datos['apellidos'],
                $datos['email'],
                $datos['rol']
            );

            $filas = $this->usuarioDAO->crear($usuario);

            $this->enviarRespuesta([
                'mensaje' => 'Usuario creado correctamente',
                'filas_afectadas' => $filas
            ], 201);

        } catch (PDOException $ex) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al crear el usuario',
                'error' => $ex->getMessage()
            ], 500);
        }
    }

    public function modificar(int $id_usuario): void {
        try {
            $usuarioExistente = $this->usuarioDAO->recuperaPorId($id_usuario);

            if (!$usuarioExistente) {
                $this->enviarRespuesta([
                    'mensaje' => 'Usuario no encontrado'
                ], 404);
            }

            $datos = $this->obtenerDatosJson();

            if (!$datos || !$this->validarDatosUsuario($datos)) {
                $this->enviarRespuesta([
                    'mensaje' => 'Datos de usuario incompletos o inválidos'
                ], 400);
            }

            $usuario = new Usuario(
                $id_usuario,
                $datos['firebase_uid'],
                $datos['nombre'],
                $datos['apellidos'],
                $datos['email'],
                $datos['rol']
            );

            $filas = $this->usuarioDAO->modificar($usuario);

            $this->enviarRespuesta([
                'mensaje' => 'Usuario modificado correctamente',
                'filas_afectadas' => $filas
            ]);

        } catch (PDOException $ex) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al modificar el usuario',
                'error' => $ex->getMessage()
            ], 500);
        }
    }

    public function eliminar(int $id_usuario): void {
        try {
            $usuarioExistente = $this->usuarioDAO->recuperaPorId($id_usuario);

            if (!$usuarioExistente) {
                $this->enviarRespuesta([
                    'mensaje' => 'Usuario no encontrado'
                ], 404);
            }

            $filas = $this->usuarioDAO->eliminar($id_usuario);

            $this->enviarRespuesta([
                'mensaje' => 'Usuario eliminado correctamente',
                'filas_afectadas' => $filas
            ]);

        } catch (PDOException $ex) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al eliminar el usuario',
                'error' => $ex->getMessage()
            ], 500);
        }
    }

    public function obtenerPorFirebaseUid(string $firebase_uid): void {
        try {
            $usuario = $this->usuarioDAO->recuperaPorFirebaseUid($firebase_uid);

            if (!$usuario) {
                $this->enviarRespuesta([
                    'mensaje' => 'Usuario no encontrado'
                ], 404);
            }

            $this->enviarRespuesta([
                'id_usuario' => $usuario->getId_usuario(),
                'firebase_uid' => $usuario->getFirebase_uid(),
                'nombre' => $usuario->getNombre(),
                'apellidos' => $usuario->getApellidos(),
                'email' => $usuario->getEmail(),
                'rol' => $usuario->getRol()
            ]);

        } catch (PDOException $ex) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al recuperar el usuario por Firebase UID',
                'error' => $ex->getMessage()
            ], 500);
        }
    }

    public function obtenerPorEmail(string $email): void {
        try {
            $usuario = $this->usuarioDAO->recuperaPorEmail($email);

            if (!$usuario) {
                $this->enviarRespuesta([
                    'mensaje' => 'Usuario no encontrado'
                ], 404);
            }

            $this->enviarRespuesta([
                'id_usuario' => $usuario->getId_usuario(),
                'firebase_uid' => $usuario->getFirebase_uid(),
                'nombre' => $usuario->getNombre(),
                'apellidos' => $usuario->getApellidos(),
                'email' => $usuario->getEmail(),
                'rol' => $usuario->getRol()
            ]);

        } catch (PDOException $ex) {
            $this->enviarRespuesta([
                'mensaje' => 'Error al recuperar el usuario por email',
                'error' => $ex->getMessage()
            ], 500);
        }
    }
}

