<?php

namespace App\Modelo\Entidades;

class ReservaAsiento {

    private ?int $id;
    private int $id_reserva;
    private int $id_asiento;

    public function __construct(
        ?int $id,
        int $id_reserva,
        int $id_asiento
    ) {
        $this->id = $id;
        $this->id_reserva = $id_reserva;
        $this->id_asiento = $id_asiento;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getId_reserva(): int {
        return $this->id_reserva;
    }

    public function getId_asiento(): int {
        return $this->id_asiento;
    }
}

