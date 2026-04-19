<?php
namespace App\Modelo\Conexion;

use PDO;

class BD {

    protected static ?PDO $bd = null;

    const HOST = '127.0.0.1';
    const DATABASE = 'gestion_cine';
    const USERNAME = 'root';
    const PASSWORD = '';

    private function __construct() {
        self::$bd = new PDO(
            "mysql:host=" . BD::HOST .
            ";dbname=" . BD::DATABASE .
            ";charset=utf8mb4",
            BD::USERNAME,
            BD::PASSWORD
        );

        self::$bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getConexion(): PDO {
        if (!self::$bd) {
            new BD();
        }

        return self::$bd;
    }
}
