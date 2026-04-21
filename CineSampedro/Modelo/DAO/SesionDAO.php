<?php

namespace App\Modelo\DAO;

use PDO;
use App\Modelo\Entidades\Sesion;
use App\Modelo\Conexion;


class SesionDAO{
    private PDO $bd;
    
    public function __construct(PDO $bd) {
        $this->bd = $bd;
    }
    
    public function crear(Sesion $sesion): int{
        $sql = "INSERT INTO sesiones (fecha_hora, precio, id_pelicula, id_sala)"
                . "VALUES (:fecha_hora, :precio, :id_pelicula, :id_sala)";
        
        $resultado = $this->bd->prepare($sql);
        
        $resultado->execute([
            ':fecha_hora' => $sesion->getFecha_hora(),
            ':precio' => $sesion->getPrecio(),
            ':id_pelicula' => $sesion->getId_pelicula(),
            ':id_sala' => $sesion->getId_sesion()
        ]);
        
        return $resultado->rowCount();
    }
    
    public function modificar(Sesion $sesion): int{
        $sql = "UPDATE sesiones
                SET fecha_hora = :fecha_hora,
                    precio = :precio,
                    id_pelicula = :id_pelicula,
                    id_sala = :id_sala
                WHERE id_sesion = :id_sesion";

        $resultado = $this->bd->prepare($sql);

        $resultado->execute([
            ':fecha_hora' => $sesion->getFechaHora(),
            ':precio' => $sesion->getPrecio(),
            ':id_pelicula' => $sesion->getIdPelicula(),
            ':id_sala' => $sesion->getIdSala(),
            ':id_sesion' => $sesion->getIdSesion()
        ]);

        return $resultado->rowCount();
    }
    
    public function eliminar(int $id_sesion): int {
        $sql = "DELETE FROM sesiones WHERE id_sesion = :id_sesion";
        
        $resultado = $this->bd->prepare($sql);
        
        $resultado->execute([
            'id_sesion' => $id_sesion
        ]);
        
        return $resultado->rowCount();
    }
    
    public function recuperarPorId(int $id_sesion): ?Sesion {
        $this->bd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        
        $sql = "SELECT * FROM sesiones WHERE id_sesion = :id_sesion";
        
        $resultado = $this->bd->prepare($sql);
        
        $resultado->execute([
            ':id_sesion' => $id_sesion
        ]);
        
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);
        
        if(!$fila){
            return null;
        }
        
        return new Sesion(
                $fila['id_sesion'],
                $fila['fecha_hora'],
                (float) $fila['precio'],
                $fila['id_pelicula'],
                $fila['id_sala']
        );
    }
    
    public function recuperarTodos(): array{
        $sesiones = [];
        
        $sql ="SELECT * FROM sesiones ORDER BY fecha_hora";
        
        $resultado = $this->bd->prepare($sql);
        $resultado->execute();
        
        foreach($resultado->fetchAll(PDO::FETCH_ASSOC) as $fila){
            $sesiones[] = new Sesion(
                $fila['id_sesion'],
                $fila['fecha_hora'],
                (float) $fila['precio'],
                $fila['id_pelicula'],
                $fila['id_sala']                    
            );
        }
        
        return $sesiones;
    }
}