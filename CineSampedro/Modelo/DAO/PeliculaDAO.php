<?php

namespace App\Modelo\DAO;

use App\Modelo\Entidades\Pelicula;  //importamos para poder crear objetos de esta clase
use PDO;  //impportamos para trabajar con la consulta

class PeliculaDAO {

    private PDO $bd;

    public function __construct(PDO $bd) {  //se le pasa la conexión
        $this->bd = $bd;
    }

    public function crear(Pelicula $pelicula): int {
        $sql = "INSERT INTO peliculas 
                (titulo, sinopsis, duracion_min, imagen, id_clasificacion)
                VALUES 
                (:titulo, :sinopsis, :duracion_min, :imagen, :id_clasificacion)";

        $resultado = $this->bd->prepare($sql);

        $resultado->execute([
            ':titulo' => $pelicula->getTitulo(),
            ':sinopsis' => $pelicula->getSinopsis(),
            ':duracion_min' => $pelicula->getDuracion_min(),
            ':imagen' => $pelicula->getImagen(),
            ':id_clasificacion' => $pelicula->getId_clasificacion()
        ]);

        return $resultado->rowCount();
    }

    public function modificar(Pelicula $pelicula): int {
        $sql = "UPDATE peliculas 
                SET titulo = :titulo,
                    sinopsis = :sinopsis,
                    duracion_min = :duracion_min,
                    imagen = :imagen,
                    id_clasificacion = :id_clasificacion
                WHERE id_pelicula = :id_pelicula";

        $resultado = $this->bd->prepare($sql);

        $resultado->execute([
            ':titulo' => $pelicula->getTitulo(),
            ':sinopsis' => $pelicula->getSinopsis(),
            ':duracion_min' => $pelicula->getDuracion_min(),
            ':imagen' => $pelicula->getImagen(),
            ':id_clasificacion' => $pelicula->getId_clasificacion(),
            ':id_pelicula' => $pelicula->getIdPelicula()
        ]);

        return $resultado->rowCount();
    }

    public function eliminar(int $id_pelicula): int {
        $sql = "DELETE FROM peliculas WHERE id_pelicula = :id_pelicula";

        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':id_pelicula' => $id_pelicula
        ]);

        return $resultado->rowCount();
    }

    public function recuperaPorId(int $id_pelicula): ?Pelicula {
        $this->bd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

        $sql = "SELECT * FROM peliculas WHERE id_pelicula = :id_pelicula";
        $resultado = $this->bd->prepare($sql);
        $resultado->execute([
            ':id_pelicula' => $id_pelicula
        ]);

        $resultado->setFetchMode(PDO::FETCH_CLASS, Pelicula::class);
        return $resultado->fetch() ?: null;
    }

    public function recuperaTodos(): \Generator {
        $this->bd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

        try {
            $this->bd->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);

            $sql = "SELECT * FROM peliculas ORDER BY titulo";
            $resultado = $this->bd->prepare($sql);
            $resultado->execute();

            $resultado->setFetchMode(PDO::FETCH_CLASS, Pelicula::class);

            while ($pelicula = $resultado->fetch()) {
                yield $pelicula;
            }

        } finally {
            $this->bd->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        }
    }
}
