<?php

namespace App\Modelo\Entidades;

class Sala {
    private ?int $id_sala;
    private int $numero;
    private int $capacidad;
    
    
    public function __construct(
            ?int $id_sala,
            int $numero,
            int $capacidad
    ){
        $this->id_sala = $id_sala;
        $this->numero = $numero;
        $this->capacidad = $capacidad;
    }
    
    public function getId_sala(): ?int {
        return $this->id_sala;
    }

    public function getNumero(): int {
        return $this->numero;
    }

    public function getCapacidad(): int {
        return $this->capacidad;
    }    
}

