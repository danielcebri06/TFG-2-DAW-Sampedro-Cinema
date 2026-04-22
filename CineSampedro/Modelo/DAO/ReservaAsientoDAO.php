<?php

namespace App\Modelo\DAO;

use App\Modelo\Entidades\ReservaAsiento;
use PDO;

class ReservaAsientoDAO {

    private PDO $bd;

    public function __construct(PDO $bd) {
        $this->bd = $bd;
    }

    public function crear(ReservaAsiento $reservaAsiento): int {
        $sql = "INSERT INTO reserva_asientos
                (id_reserva, id_asiento)
                VALUES
                (:id_reserva, :id_asiento)";

        $resultado = $this->bd->prepare($sql);

        $resultado->execute([
            ':id_reserva' => $reservaAsiento->getId_reserva(),
            ':id_asiento' => $reservaAsiento->getId_asiento()
        ]);

        return $resultado->rowCount();
    }

    public function modificar(ReservaAsiento $reservaAsiento): int {
        $sql = "UPDATE reserva_asientos
                SET id_reserva = :id_reserva,
                    id_asiento = :id_asiento
                WHERE id = :id";

        $resultado = $this->bd->prepare($sql);

        $resultado->execute([
            ':id_reserva' => $reservaAsiento->getId_reserva(),
            ':id_asiento' => $reservaAsiento->getId_asiento(),
            ':id' => $reservaAsiento->getId()
        ]);

        return $resultado->rowCount();
    }

    public function eliminar(int $id): int {
        $sql = "DELETE FROM reserva_asientos WHERE id = :id";

        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':id' => $id
        ]);

        return $resultado->rowCount();
    }

    public function recuperaPorId(int $id): ?ReservaAsiento {
        $sql = "SELECT * FROM reserva_asientos WHERE id = :id";

        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':id' => $id
        ]);

        $fila = $resultado->fetch(PDO::FETCH_ASSOC);

        if (!$fila) {
            return null;
        }

        return new ReservaAsiento(
            $fila['id'],
            $fila['id_reserva'],
            $fila['id_asiento']
        );
    }

    public function recuperaTodos(): array {
        $reservaAsientos = [];

        $sql = "SELECT * FROM reserva_asientos ORDER BY id";
        $resultado = $this->bd->prepare($sql);
        $resultado->execute();

        foreach ($resultado->fetchAll(PDO::FETCH_ASSOC) as $fila) {
            $reservaAsientos[] = new ReservaAsiento(
                $fila['id'],
                $fila['id_reserva'],
                $fila['id_asiento']
            );
        }

        return $reservaAsientos;
    }

    public function recuperaPorReserva(int $id_reserva): array {
        $reservaAsientos = [];

        $sql = "SELECT * FROM reserva_asientos
                WHERE id_reserva = :id_reserva
                ORDER BY id";

        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':id_reserva' => $id_reserva
        ]);

        foreach ($resultado->fetchAll(PDO::FETCH_ASSOC) as $fila) {
            $reservaAsientos[] = new ReservaAsiento(
                $fila['id'],
                $fila['id_reserva'],
                $fila['id_asiento']
            );
        }

        return $reservaAsientos;
    }

    public function recuperaPorAsiento(int $id_asiento): array {
        $reservaAsientos = [];

        $sql = "SELECT * FROM reserva_asientos
                WHERE id_asiento = :id_asiento
                ORDER BY id";

        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':id_asiento' => $id_asiento
        ]);

        foreach ($resultado->fetchAll(PDO::FETCH_ASSOC) as $fila) {
            $reservaAsientos[] = new ReservaAsiento(
                $fila['id'],
                $fila['id_reserva'],
                $fila['id_asiento']
            );
        }

        return $reservaAsientos;
    }
}

