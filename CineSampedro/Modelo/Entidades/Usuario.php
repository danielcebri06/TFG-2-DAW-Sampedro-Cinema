<?php

namespace App\Modelo\Entidades;

class Usuario {

    private ?int $id_usuario;
    private string $firebase_uid;
    private string $nombre;
    private string $apellidos;
    private string $email;
    private string $rol;

    public function __construct(
        ?int $id_usuario,
        string $firebase_uid,
        string $nombre,
        string $apellidos,
        string $email,
        string $rol
    ) {
        $this->id_usuario = $id_usuario;
        $this->firebase_uid = $firebase_uid;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->email = $email;
        $this->rol = $rol;
    }

    public function getId_usuario(): ?int {
        return $this->id_usuario;
    }

    public function getFirebase_uid(): string {
        return $this->firebase_uid;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getApellidos(): string {
        return $this->apellidos;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getRol(): string {
        return $this->rol;
    }
}
