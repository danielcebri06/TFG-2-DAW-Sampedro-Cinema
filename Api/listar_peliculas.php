<?php
require_once __DIR__ . '/../CineSampedro/vendor/autoload.php';
$controlador = new \App\Controlador\ApiPeliculaController();
$controlador->listar();