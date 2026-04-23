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
                (id_sala, fila, numero)
                VALUES
                (:id_sala, :fila, :numero)";

        $resultado = $this->bd->prepare($sql);

        $resultado->execute([
            ':id_sala' => $asiento->getId_sala(),
            ':fila' => $asiento->getFila(),
            ':numero' => $asiento->getNumero()
        ]);

        return $resultado->rowCount();
    }

    public function modificar(Asiento $asiento): int {

$sql = "UPDATE asientos
                SET id_sala = :id_sala,
                    fila = :fila,
                    numero = :numero
                WHERE id = :id_asiento";

        $resultado = $this->bd->prepare($sql);

        $resultado->execute([
            ':id_sala' => $asiento->getId_sala(),
            ':fila' => $asiento->getFila(),
            ':numero' => $asiento->getNumero(),
            ':id_asiento' => $asiento->getId_asiento()
        ]);

        return $resultado->rowCount();
    }

    public function eliminar(int $id_asiento): int {

    $sql = "DELETE FROM asientos WHERE id = :id_asiento";

        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':id_asiento' => $id_asiento
        ]);

        return $resultado->rowCount();
    }

    public function recuperaPorId(int $id_asiento): ?Asiento {

    $sql = "SELECT * FROM asientos WHERE id = :id_asiento";

        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':id_asiento' => $id_asiento
        ]);

        $fila = $resultado->fetch(PDO::FETCH_ASSOC);

        if (!$fila) {
            return null;
        }

        return new Asiento(
            $fila['id'],
            $fila['id_sala'],
            $fila['fila'],
            $fila['numero']
        );
    }

    public function recuperaTodos(): array {
        $asientos = [];

        $sql = "SELECT * FROM asientos ORDER BY id_sala, fila, numero";
        $resultado = $this->bd->prepare($sql);
        $resultado->execute();

        foreach ($resultado->fetchAll(PDO::FETCH_ASSOC) as $fila) {
            $asientos[] = new Asiento(
                $fila['id'], 
                $fila['id_sala'],
                $fila['fila'],
                $fila['numero']
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
                $fila['id'], 
                $fila['id_sala'],
                $fila['fila'],
                $fila['numero']
            );
        }

        return $asientos;
    }

    // NUEVA FUNCIÓN: Cruza la tabla de asientos con las reservas de la sesión
    public function recuperarEstadoAsientosPorSesion(int $id_sala, int $id_sesion): array {
        $sql = "SELECT a.id, a.fila, a.numero, 
                       CASE WHEN ra.id_asiento IS NOT NULL THEN 'Ocupado' ELSE 'Libre' END AS estado
                FROM asientos a
                LEFT JOIN reserva_asientos ra ON a.id = ra.id_asiento 
                     AND ra.id_reserva IN (SELECT id FROM reservas WHERE id_sesion = :id_sesion)
                WHERE a.id_sala = :id_sala
                ORDER BY a.fila, a.numero"; // Los ordenamos para que el mapa en Angular sea coherente
                
        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':id_sala' => $id_sala,
            ':id_sesion' => $id_sesion
        ]);
        
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }
}