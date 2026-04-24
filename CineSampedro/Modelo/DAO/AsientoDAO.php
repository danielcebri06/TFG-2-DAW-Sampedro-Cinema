<?php

namespace App\Modelo\DAO;

use App\Modelo\Entidades\Asiento;
use PDO;

class AsientoDAO {

    private PDO $bd;

    public function __construct(PDO $bd) {
        $this->bd = $bd;
    }

    public function crear(Asiento $asiento): int {
        $sql = "INSERT INTO asientos
                (id_sala, fila, numero, tipo)
                VALUES
                (:id_sala, :fila, :numero, :tipo)";

        $resultado = $this->bd->prepare($sql);

        $resultado->execute([
            ':id_sala' => $asiento->getId_sala(),
            ':fila' => $asiento->getFila(),
            ':numero' => $asiento->getNumero(),
            ':tipo' => $asiento->getTipo()
        ]);

        return $resultado->rowCount();
    }

    public function modificar(Asiento $asiento): int {

$sql = "UPDATE asientos
                SET id_sala = :id_sala,
                    fila = :fila,
                    numero = :numero,
                    tipo = :tipo
                WHERE id_asiento = :id_asiento";

        $resultado = $this->bd->prepare($sql);

        $resultado->execute([
            ':id_sala' => $asiento->getId_sala(),
            ':fila' => $asiento->getFila(),
            ':numero' => $asiento->getNumero(),
            ':tipo' => $asiento->getTipo(),
            ':id_asiento' => $asiento->getId_asiento()
        ]);

        return $resultado->rowCount();
    }

    public function eliminar(int $id_asiento): int {

    $sql = "DELETE FROM asientos WHERE id_asiento = :id_asiento";

        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':id_asiento' => $id_asiento
        ]);

        return $resultado->rowCount();
    }

    public function recuperaPorId(int $id_asiento): ?Asiento {

    $sql = "SELECT * FROM asientos WHERE id_asiento = :id_asiento";

        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':id_asiento' => $id_asiento
        ]);

        $fila = $resultado->fetch(PDO::FETCH_ASSOC);

        if (!$fila) {
            return null;
        }

        return new Asiento(
            (int) $fila['id_asiento'],
            (int) $fila['id_sala'],
            (int) $fila['fila'],
            (int) $fila['numero'],
            $fila['tipo']
        );
    }

    public function recuperaTodos(): array {
        $asientos = [];

        $sql = "SELECT * FROM asientos ORDER BY id_sala, fila, numero";
        $resultado = $this->bd->prepare($sql);
        $resultado->execute();

        foreach ($resultado->fetchAll(PDO::FETCH_ASSOC) as $fila) {
            $asientos[] = new Asiento(
                (int) $fila['id_asiento'],
                (int) $fila['id_sala'],
                (int) $fila['fila'],
                (int) $fila['numero'],
                $fila['tipo']
            );
        }

        return $asientos;
    }

    public function recuperaPorSala(int $id_sala): array {
        $asientos = [];

        $sql = "SELECT * FROM asientos
                WHERE id_sala = :id_sala
                ORDER BY fila, numero";

        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':id_sala' => $id_sala
        ]);

        foreach ($resultado->fetchAll(PDO::FETCH_ASSOC) as $fila) {
            $asientos[] = new Asiento(
                (int) $fila['id_asiento'],
                (int) $fila['id_sala'],
                (int) $fila['fila'],
                (int) $fila['numero'],
                $fila['tipo']
            );
        }

        return $asientos;
    }

    // Esta tabla ya modela el estado por sesion y asiento en bd_cinema.
    public function recuperarEstadoAsientosPorSesion(int $id_sala, int $id_sesion): array {
        $sql = "SELECT a.id_asiento, a.fila, a.numero, a.tipo,
                       CASE sa.estado
                           WHEN 'ocupado' THEN 'Ocupado'
                           WHEN 'en_proceso' THEN 'Reservado'
                           ELSE 'Libre'
                       END AS estado
                FROM asientos a
                INNER JOIN sesion_asientos sa
                    ON sa.id_asiento = a.id_asiento AND sa.id_sesion = :id_sesion
                WHERE a.id_sala = :id_sala
                ORDER BY a.fila, a.numero";
                
        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':id_sala' => $id_sala,
            ':id_sesion' => $id_sesion
        ]);
        
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }
}