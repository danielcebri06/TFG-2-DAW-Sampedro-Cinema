<?php

namespace App\Modelo\DAO;

use App\Modelo\Entidades\Usuario;
use PDO;

class UsuarioDAO {

    private PDO $bd;

    public function __construct(PDO $bd) {
        $this->bd = $bd;
    }

    public function crear(Usuario $usuario): int {
        $sql = "INSERT INTO usuarios
                (firebase_uid, nombre, apellidos, email, rol)
                VALUES
                (:firebase_uid, :nombre, :apellidos, :email, :rol)";

        $resultado = $this->bd->prepare($sql);

        $resultado->execute([
            ':firebase_uid' => $usuario->getFirebase_uid(),
            ':nombre' => $usuario->getNombre(),
            ':apellidos' => $usuario->getApellidos(),
            ':email' => $usuario->getEmail(),
            ':rol' => $usuario->getRol()
        ]);

        return $resultado->rowCount();
    }

    public function modificar(Usuario $usuario): int {
        $sql = "UPDATE usuarios
                SET firebase_uid = :firebase_uid,
                    nombre = :nombre,
                    apellidos = :apellidos,
                    email = :email,
                    rol = :rol
                WHERE id_usuario = :id_usuario";

        $resultado = $this->bd->prepare($sql);

        $resultado->execute([
            ':firebase_uid' => $usuario->getFirebase_uid(),
            ':nombre' => $usuario->getNombre(),
            ':apellidos' => $usuario->getApellidos(),
            ':email' => $usuario->getEmail(),
            ':rol' => $usuario->getRol(),
            ':id_usuario' => $usuario->getId_usuario()
        ]);

        return $resultado->rowCount();
    }

    public function eliminar(int $id_usuario): int {
        $sql = "DELETE FROM usuarios WHERE id_usuario = :id_usuario";

        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':id_usuario' => $id_usuario
        ]);

        return $resultado->rowCount();
    }

    public function recuperaPorId(int $id_usuario): ?Usuario {
        $sql = "SELECT * FROM usuarios WHERE id_usuario = :id_usuario";

        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':id_usuario' => $id_usuario
        ]);

        $fila = $resultado->fetch(PDO::FETCH_ASSOC);

        if (!$fila) {
            return null;
        }

        return new Usuario(
            $fila['id_usuario'],
            $fila['firebase_uid'],
            $fila['nombre'],
            $fila['apellidos'],
            $fila['email'],
            $fila['rol']
        );
    }

    public function recuperaTodos(): array {
        $usuarios = [];

        $sql = "SELECT * FROM usuarios ORDER BY apellidos, nombre";
        $resultado = $this->bd->prepare($sql);
        $resultado->execute();

        foreach ($resultado->fetchAll(PDO::FETCH_ASSOC) as $fila) {
            $usuarios[] = new Usuario(
                $fila['id_usuario'],
                $fila['firebase_uid'],
                $fila['nombre'],
                $fila['apellidos'],
                $fila['email'],
                $fila['rol']
            );
        }

        return $usuarios;
    }

    public function recuperaPorFirebaseUid(string $firebase_uid): ?Usuario {
        $sql = "SELECT * FROM usuarios WHERE firebase_uid = :firebase_uid";

        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':firebase_uid' => $firebase_uid
        ]);

        $fila = $resultado->fetch(PDO::FETCH_ASSOC);

        if (!$fila) {
            return null;
        }

        return new Usuario(
            $fila['id_usuario'],
            $fila['firebase_uid'],
            $fila['nombre'],
            $fila['apellidos'],
            $fila['email'],
            $fila['rol']
        );
    }

    public function recuperaPorEmail(string $email): ?Usuario {
        $sql = "SELECT * FROM usuarios WHERE email = :email";

        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':email' => $email
        ]);

        $fila = $resultado->fetch(PDO::FETCH_ASSOC);

        if (!$fila) {
            return null;
        }

        return new Usuario(
            $fila['id_usuario'],
            $fila['firebase_uid'],
            $fila['nombre'],
            $fila['apellidos'],
            $fila['email'],
            $fila['rol']
        );
    }
}

