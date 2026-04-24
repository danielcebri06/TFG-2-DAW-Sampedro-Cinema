<?php

namespace App\Modelo\DAO;

use App\Modelo\Entidades\Pago;
use PDO;

class PagoDAO {

    private PDO $bd;

    public function __construct(PDO $bd) {
        $this->bd = $bd;
    }

    public function crear(Pago $pago): int {
        $sql = "INSERT INTO pagos
                (id_reserva, importe, estado, fecha_hora, stripe_payment_intent_id)
                VALUES
                (:id_reserva, :importe, :estado, :fecha_hora, :stripe_payment_intent_id)";

        $resultado = $this->bd->prepare($sql);

        $resultado->execute([
            ':id_reserva' => $pago->getId_reserva(),
            ':importe' => $pago->getImporte(),
            ':estado' => $pago->getEstado(),
            ':fecha_hora' => $pago->getFecha_hora(),
            ':stripe_payment_intent_id' => $pago->getStripe_payment_intent_id()
        ]);

        return $resultado->rowCount();
    }

    public function modificar(Pago $pago): int {
        $sql = "UPDATE pagos
                SET id_reserva = :id_reserva,
                    importe = :importe,
                    estado = :estado,
                    fecha_hora = :fecha_hora,
                    stripe_payment_intent_id = :stripe_payment_intent_id
                WHERE id_pago = :id_pago";

        $resultado = $this->bd->prepare($sql);

        $resultado->execute([
            ':id_reserva' => $pago->getId_reserva(),
            ':importe' => $pago->getImporte(),
            ':estado' => $pago->getEstado(),
            ':fecha_hora' => $pago->getFecha_hora(),
            ':stripe_payment_intent_id' => $pago->getStripe_payment_intent_id(),
            ':id_pago' => $pago->getId_pago()
        ]);

        return $resultado->rowCount();
    }

    public function eliminar(int $id_pago): int {
        $sql = "DELETE FROM pagos WHERE id_pago = :id_pago";

        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':id_pago' => $id_pago
        ]);

        return $resultado->rowCount();
    }

    public function recuperaPorId(int $id_pago): ?Pago {
        $sql = "SELECT * FROM pagos WHERE id_pago = :id_pago";

        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':id_pago' => $id_pago
        ]);

        $fila = $resultado->fetch(PDO::FETCH_ASSOC);

        if (!$fila) {
            return null;
        }

        return new Pago(
            $fila['id_pago'],
            $fila['id_reserva'],
            (float) $fila['importe'],
            $fila['estado'],
            $fila['fecha_hora'],
            $fila['stripe_payment_intent_id']
        );
    }

    public function recuperaTodos(): array {
        $pagos = [];

        $sql = "SELECT * FROM pagos ORDER BY fecha_hora DESC";
        $resultado = $this->bd->prepare($sql);
        $resultado->execute();

        foreach ($resultado->fetchAll(PDO::FETCH_ASSOC) as $fila) {
            $pagos[] = new Pago(
                $fila['id_pago'],
                $fila['id_reserva'],
                (float) $fila['importe'],
                $fila['estado'],
                $fila['fecha_hora'],
                $fila['stripe_payment_intent_id']
            );
        }

        return $pagos;
    }

    public function recuperaPorReserva(int $id_reserva): array {
        $pagos = [];

        $sql = "SELECT * FROM pagos
                WHERE id_reserva = :id_reserva
                ORDER BY fecha_hora DESC";

        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':id_reserva' => $id_reserva
        ]);

        foreach ($resultado->fetchAll(PDO::FETCH_ASSOC) as $fila) {
            $pagos[] = new Pago(
                $fila['id_pago'],
                $fila['id_reserva'],
                (float) $fila['importe'],
                $fila['estado'],
                $fila['fecha_hora'],
                $fila['stripe_payment_intent_id']
            );
        }

        return $pagos;
    }

    public function actualizarEstado(int $id_pago, string $estado): int {
        $sql = "UPDATE pagos
                SET estado = :estado
                WHERE id_pago = :id_pago";

        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':estado' => $estado,
            ':id_pago' => $id_pago
        ]);

        return $resultado->rowCount();
    }
}

