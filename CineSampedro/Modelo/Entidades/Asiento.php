<?php

namespace App\Modelo\Entidades;

class Asiento {

    private ?int $id_asiento;
    private int $id_sala;
    private string $fila;
    private int $numero;

    public function __construct(
        ?int $id_asiento,
        int $id_sala,
        string $fila,
        int $numero
    ) {
        $this->id_asiento = $id_asiento;
        $this->id_sala = $id_sala;
        $this->fila = $fila;
        $this->numero = $numero;
    }

    public function getId_asiento(): ?int {
        return $this->id_asiento;
    }

    public function getId_sala(): int {
        return $this->id_sala;
    }

    public function getFila(): string {
        return $this->fila;
    }

    public function getNumero(): int {
        return $this->numero;
    }
}
