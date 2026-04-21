<?php

namespace App\Modelo\Entidades;

class Sesion {
    private ?int $id_sesion;
    private string $fecha_hora;
    private float $precio;
    private int $id_pelicula;
    private int $id_sala;
    
    public function __construct(
            ?int $id_sesion,string $fecha_hora,
            float $precio, int $id_pelicula, int $id_sala    
    ){
        $this->id_sesion = $id_sesion;
        $this->fecha_hora = $fecha_hora;
        $this->precio = $precio;
        $this->id_pelicula = $id_pelicula;
        $this->id_sala = $id_sala;
    }
    
    public function getId_sesion(): ?int {
        return $this->id_sesion;
    }

    public function getFecha_hora(): string {
        return $this->fecha_hora;
    }

    public function getPrecio(): float {
        return $this->precio;
    }

    public function getId_pelicula(): int {
        return $this->id_pelicula;
    }

    public function getId_sala(): int {
        return $this->id_sala;
    }


}
