<?php

namespace App\Modelo\Entidades;

class Pelicula{
    private ?int $id_pelicula;
    private string $titulo;
    private string $sinopsis;
    private int $duracion_minutos;
    private string $imagen;
    private ?string $fecha_estreno;
    private int $id_clasificacion;

    public function __construct(
            ?int $id_pelicula, string $titulo, 
            string $sinopsis, int $duracion_minutos,
            string $imagen, ?string $fecha_estreno, int $id_clasificacion
    ) {
        $this->id_pelicula = $id_pelicula;
        $this->titulo = $titulo;
        $this->sinopsis = $sinopsis;
        $this->duracion_minutos = $duracion_minutos;
        $this->imagen = $imagen;
        $this->fecha_estreno = $fecha_estreno;
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

    public function getDuracion_minutos(): int {
        return $this->duracion_minutos;
    }

    public function getDuracion_min(): int {
        return $this->duracion_minutos;
    }

    public function getImagen(): string {
        return $this->imagen;
    }

    public function getFecha_estreno(): ?string {
        return $this->fecha_estreno;
    }

    public function getId_clasificacion(): int {
        return $this->id_clasificacion;
    }
}

