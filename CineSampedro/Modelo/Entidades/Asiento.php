<?php

namespace App\Modelo\Entidades;

class Asiento {

    private ?int $id_asiento;
    private int $id_sala;
    private int $fila;
    private int $numero;
    private string $tipo;

    public function __construct(
        ?int $id_asiento,
        int $id_sala,
        int $fila,
        int $numero,
        string $tipo = 'normal'
    ) {
        $this->id_asiento = $id_asiento;
        $this->id_sala = $id_sala;
        $this->fila = $fila;
        $this->numero = $numero;
        $this->tipo = $tipo;
    }

    public function getId_asiento(): ?int {
        return $this->id_asiento;
    }

    public function getId_sala(): int {
        return $this->id_sala;
    }

    public function getFila(): int {
        return $this->fila;
    }

    public function getNumero(): int {
        return $this->numero;
    }

    public function getTipo(): string {
        return $this->tipo;
    }
}
