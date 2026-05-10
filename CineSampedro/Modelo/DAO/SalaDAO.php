<?php

namespace App\Modelo\DAO;

use App\Modelo\Entidades\Sala;
use PDO;

class SalaDAO {
    private PDO $bd;
    
    public function __construct(PDO $bd){
        $this->bd = $bd;
    }
    
    public function crear (Sala $sala): int {
        $sql = "INSERT INTO salas (numero, capacidad) VALUES (:numero, :capacidad)";
        
        $resultado = $this->bd->prepare($sql);
        
        $resultado->execute([
            ':numero' => $sala->getNumero(),
            ':capacidad' => $sala->getCapacidad()
        ]);
        
        return $resultado->rowCount();
    }
    
    public function modificar (Sala $sala): int {
        $sql = "UPDATE salas SET numero = :numero, capacidad = :capacidad "
                . "WHERE id_sala = :id_sala";
        
        $resultado = $this->bd->prepare($sql);
        
        $resultado->execute([
            ':numero' => $sala->getNumero(),
            ':capacidad' => $sala->getCapacidad(),
            ':id_sala' => $sala->getId_sala()
        ]);
        
        return $resultado->rowCount();
    }
    
    public function eliminar (int $id_sala): int {
        $sql = "DELETE FROM salas WHERE id_sala = :id_sala";
        
        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':id_sala' => $id_sala
        ]);
        
        return $resultado->rowCount();
        
    }
    
    public function recuperarPorId (int $id_sala): ?Sala {
        $sql = "SELECT * FROM salas WHERE id_sala = :id_sala";
        
        $resultado = $this->bd->prepare($sql);
        
        $resultado->execute([
            ':id_sala' => $id_sala
        ]);
        
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);
        
        if (!$fila){
            return null;
        }
        
        return new Sala(
                $fila['id_sala'],
                $fila['numero'],
                $fila['capacidad']
        );
    }

    public function recuperarPorNumero(int $numero): ?Sala {
        $sql = "SELECT * FROM salas WHERE numero = :numero";

        $resultado = $this->bd->prepare($sql);

        $resultado->execute([
            ':numero' => $numero
        ]);

        $fila = $resultado->fetch(PDO::FETCH_ASSOC);

        if (!$fila) {
            return null;
        }

        return new Sala(
            $fila['id_sala'],
            $fila['numero'],
            $fila['capacidad']
        );
    }
    
    public function recuperarTodos (): array {
        $salas = [];
        
        $sql ="SELECT * FROM salas ORDER BY numero";
        
        $resultado = $this->bd->prepare($sql);
        $resultado->execute();
        
        foreach ($resultado->fetchAll(PDO::FETCH_ASSOC) as $fila) {
            $salas [] = new Sala (
                $fila['id_sala'],
                $fila['numero'],
                $fila['capacidad']
            );
        }
        
        return $salas;
    }
}

