<?php

namespace App\Modelo\Entidades;

class Pelicula{
    private ?int $id_pelicula;
    private string $titulo;
    private string $sinopsis;
    private int $duracion_min;
    private string $imagen;
    private int $id_clasificacion;

    public function __construct(
            ?int $id_pelicula, string $titulo, 
            string $sinopsis, int $duracion_min, 
            string $imagen,int $id_clasificacion
    ) {
        $this->id_pelicula = $id_pelicula;
        $this->titulo = $titulo;
        $this->sinopsis = $sinopsis;
        $this->duracion_min = $duracion_min;
        $this->imagen = $imagen;
        $this->id_clasificacion = $id_clasificacion;
    }
    
    public function getId_pelicula(): ?int {
        return $this->id_pelicula;
    }

    public function getTitulo(): string {
        return $this->titulo;
    }

    public function getSinopsis(): string {
        return $this->sinopsis;
    }

    public function getDuracion_min(): int {
        return $this->duracion_min;
    }

    public function getImagen(): string {
        return $this->imagen;
    }

    public function getId_clasificacion(): int {
        return $this->id_clasificacion;
    }
}

