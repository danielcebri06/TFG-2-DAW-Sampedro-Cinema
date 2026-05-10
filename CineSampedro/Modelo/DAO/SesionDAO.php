<?php
namespace App\Modelo\DAO;

use PDO;
use App\Modelo\Entidades\Sesion;

class SesionDAO {
    private PDO $bd;
    
    public function __construct(PDO $bd) {
        $this->bd = $bd;
    }
    
    public function recuperarPorPelicula(int $id_pelicula): array {
        $sql = "SELECT s.*, sa.numero as numero_sala 
                FROM sesiones s 
                JOIN salas sa ON s.id_sala = sa.id_sala 
                WHERE s.id_pelicula = :id_pelicula 
                ORDER BY s.fecha_hora";
        
        $resultado = $this->bd->prepare($sql);
        $resultado->execute([':id_pelicula' => $id_pelicula]);
        
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarPorSala(int $id_sala): int {
        $sql = "SELECT COUNT(*) FROM sesiones WHERE id_sala = :id_sala";

        $resultado = $this->bd->prepare($sql);

        $resultado->execute([
            ':id_sala' => $id_sala
        ]);

        return (int) $resultado->fetchColumn();
    }
    
    public function crear(Sesion $sesion): int{
        $sql = "INSERT INTO sesiones (fecha_hora, precio, id_pelicula, id_sala)"
                . "VALUES (:fecha_hora, :precio, :id_pelicula, :id_sala)";
        
        $resultado = $this->bd->prepare($sql);
        
        $resultado->execute([
            ':fecha_hora' => $sesion->getFecha_hora(),
            ':precio' => $sesion->getPrecio(),
            ':id_pelicula' => $sesion->getId_pelicula(),
            ':id_sala' => $sesion->getId_sala()
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
            ':fecha_hora' => $sesion->getFecha_hora(),
            ':precio' => $sesion->getPrecio(),
            ':id_pelicula' => $sesion->getId_pelicula(),
            ':id_sala' => $sesion->getId_sala(),
            ':id_sesion' => $sesion->getId_sesion()
        ]);

        return $resultado->rowCount();
    }
    
    public function eliminar(int $id_sesion): int {
        $sql = "DELETE FROM sesiones WHERE id_sesion = :id_sesion";
        
        $resultado = $this->bd->prepare($sql);
        
        $resultado->execute([
            ':id_sesion' => $id_sesion
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