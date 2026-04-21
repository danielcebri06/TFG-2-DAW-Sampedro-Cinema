<?php

namespace App\Modelo\DAO;

use App\Modelo\Entidades\Reserva;
use PDO;

class ReservaDAO {

    private PDO $bd;

    public function __construct(PDO $bd) {
        $this->bd = $bd;
    }

    public function crear(Reserva $reserva): int {
        $sql = "INSERT INTO reservas
                (id_usuario, id_sesion, fecha_hora, estado)
                VALUES
                (:id_usuario, :id_sesion, :fecha_hora, :estado)";

        $resultado = $this->bd->prepare($sql);

        $resultado->execute([
            ':id_usuario' => $reserva->getId_usuario(),
            ':id_sesion' => $reserva->getId_sesion(),
            ':fecha_hora' => $reserva->getFecha_hora(),
            ':estado' => $reserva->getEstado()
        ]);

        return $resultado->rowCount();
    }

    public function modificar(Reserva $reserva): int {
        $sql = "UPDATE reservas
                SET id_usuario = :id_usuario,
                    id_sesion = :id_sesion,
                    fecha_hora = :fecha_hora,
                    estado = :estado
                WHERE id_reserva = :id_reserva";

        $resultado = $this->bd->prepare($sql);

        $resultado->execute([
            ':id_usuario' => $reserva->getId_usuario(),
            ':id_sesion' => $reserva->getId_sesion(),
            ':fecha_hora' => $reserva->getFecha_hora(),
            ':estado' => $reserva->getEstado(),
            ':id_reserva' => $reserva->getId_reserva()
        ]);

        return $resultado->rowCount();
    }

    public function eliminar(int $id_reserva): int {
        $sql = "DELETE FROM reservas WHERE id_reserva = :id_reserva";

        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':id_reserva' => $id_reserva
        ]);

        return $resultado->rowCount();
    }

    public function recuperaPorId(int $id_reserva): ?Reserva {
        $sql = "SELECT * FROM reservas WHERE id_reserva = :id_reserva";

        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':id_reserva' => $id_reserva
        ]);

        $fila = $resultado->fetch(PDO::FETCH_ASSOC);

        if (!$fila) {
            return null;
        }

        return new Reserva(
            $fila['id_reserva'],
            $fila['id_usuario'],
            $fila['id_sesion'],
            $fila['fecha_hora'],
            $fila['estado']
        );
    }

    public function recuperaTodos(): array {
        $reservas = [];

        $sql = "SELECT * FROM reservas ORDER BY fecha_hora DESC";
        $resultado = $this->bd->prepare($sql);
        $resultado->execute();

        foreach ($resultado->fetchAll(PDO::FETCH_ASSOC) as $fila) {
            $reservas[] = new Reserva(
                $fila['id_reserva'],
                $fila['id_usuario'],
                $fila['id_sesion'],
                $fila['fecha_hora'],
                $fila['estado']
            );
        }

        return $reservas;
    }

    public function recuperaPorUsuario(int $id_usuario): array {
        $reservas = [];

        $sql = "SELECT * FROM reservas
                WHERE id_usuario = :id_usuario
                ORDER BY fecha_hora DESC";

        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':id_usuario' => $id_usuario
        ]);

        foreach ($resultado->fetchAll(PDO::FETCH_ASSOC) as $fila) {
            $reservas[] = new Reserva(
                $fila['id_reserva'],
                $fila['id_usuario'],
                $fila['id_sesion'],
                $fila['fecha_hora'],
                $fila['estado']
            );
        }

        return $reservas;
    }

    public function recuperaPorSesion(int $id_sesion): array {
        $reservas = [];

        $sql = "SELECT * FROM reservas
                WHERE id_sesion = :id_sesion
                ORDER BY fecha_hora DESC";

        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':id_sesion' => $id_sesion
        ]);

        foreach ($resultado->fetchAll(PDO::FETCH_ASSOC) as $fila) {
            $reservas[] = new Reserva(
                $fila['id_reserva'],
                $fila['id_usuario'],
                $fila['id_sesion'],
                $fila['fecha_hora'],
                $fila['estado']
            );
        }

        return $reservas;
    }
}
