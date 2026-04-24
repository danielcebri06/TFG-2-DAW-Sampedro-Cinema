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
                (titulo, sinopsis, duracion_minutos, imagen, fecha_estreno, id_clasificacion)
                VALUES 
                (:titulo, :sinopsis, :duracion_minutos, :imagen, :fecha_estreno, :id_clasificacion)";

        $resultado = $this->bd->prepare($sql);

        $resultado->execute([
            ':titulo' => $pelicula->getTitulo(),
            ':sinopsis' => $pelicula->getSinopsis(),
            ':duracion_minutos' => $pelicula->getDuracion_minutos(),
            ':imagen' => $pelicula->getImagen(),
            ':fecha_estreno' => $pelicula->getFecha_estreno(),
            ':id_clasificacion' => $pelicula->getId_clasificacion()
        ]);

        return $resultado->rowCount();
    }

    public function modificar(Pelicula $pelicula): int {
        $sql = "UPDATE peliculas 
                SET titulo = :titulo,
                    sinopsis = :sinopsis,
                    duracion_minutos = :duracion_minutos,
                    imagen = :imagen,
                    fecha_estreno = :fecha_estreno,
                    id_clasificacion = :id_clasificacion
                WHERE id_pelicula = :id_pelicula";

        $resultado = $this->bd->prepare($sql);

        $resultado->execute([
            ':titulo' => $pelicula->getTitulo(),
            ':sinopsis' => $pelicula->getSinopsis(),
            ':duracion_minutos' => $pelicula->getDuracion_minutos(),
            ':imagen' => $pelicula->getImagen(),
            ':fecha_estreno' => $pelicula->getFecha_estreno(),
            ':id_clasificacion' => $pelicula->getId_clasificacion(),
            ':id_pelicula' => $pelicula->getId_pelicula()
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

    public function recuperaTodos(): \Generator {
    $this->bd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

    try {
        $this->bd->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);

        $sql = "SELECT * FROM peliculas ORDER BY titulo";
        $resultado = $this->bd->prepare($sql);
        $resultado->execute();

        while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
            yield new Pelicula(
                (int) $fila['id_pelicula'],
                $fila['titulo'],
                $fila['sinopsis'],
                (int) $fila['duracion_minutos'],
                $fila['imagen'],
                $fila['fecha_estreno'],
                (int)$fila['id_clasificacion']
            );
        }

    } finally {
        $this->bd->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
    }
}

public function recuperaPorId(int $id_pelicula): ?Pelicula {
    $sql = "SELECT * FROM peliculas WHERE id_pelicula = :id_pelicula";
    $resultado = $this->bd->prepare($sql);
    $resultado->execute([':id_pelicula' => $id_pelicula]);

    $fila = $resultado->fetch(PDO::FETCH_ASSOC);

    if (!$fila) return null;

    return new Pelicula(
        (int) $fila['id_pelicula'],
        $fila['titulo'],
        $fila['sinopsis'],
        (int) $fila['duracion_minutos'],
        $fila['imagen'],
        $fila['fecha_estreno'],
        (int)$fila['id_clasificacion']
    );
}
}
