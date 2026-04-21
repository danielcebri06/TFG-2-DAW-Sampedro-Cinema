<?php

namespace App\Modelo\Entidades;

class Reserva {

    private ?int $id_reserva;
    private int $id_usuario;
    private int $id_sesion;
    private string $fecha_hora;
    private string $estado;

    public function __construct(
        ?int $id_reserva,
        int $id_usuario,
        int $id_sesion,
        string $fecha_hora,
        string $estado
    ) {
        $this->id_reserva = $id_reserva;
        $this->id_usuario = $id_usuario;
        $this->id_sesion = $id_sesion;
        $this->fecha_hora = $fecha_hora;
        $this->estado = $estado;
    }

    public function getId_reserva(): ?int {
        return $this->id_reserva;
    }

    public function getId_usuario(): int {
        return $this->id_usuario;
    }

    public function getId_sesion(): int {
        return $this->id_sesion;
    }

    public function getFecha_hora(): string {
        return $this->fecha_hora;
    }

    public function getEstado(): string {
        return $this->estado;
    }
}
