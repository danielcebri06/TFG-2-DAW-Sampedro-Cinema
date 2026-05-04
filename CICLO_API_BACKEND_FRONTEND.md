# Ciclo de Conexion API -> Backend -> Frontend (Angular)

## 1. Objetivo y rol de cada carpeta

- `Api/`: capa puente simplificada para el frontend. Expone endpoints PHP directos (por ejemplo `listar_peliculas.php`) y deriva el trabajo al backend real.
- `CineSampedro/`: backend de negocio (controladores, DAO, entidades, conexion a BD).
- `Sampedro_Cinema/`: frontend Angular que consume `/api/...` y muestra cartelera, sesiones y asientos.

La carpeta `Api/` es la puerta de entrada que usa Angular en desarrollo.

## 2. Punto de union tecnico (clave)

Cada script de `Api/` hace esto:

1. Define cabeceras CORS (en varios endpoints).
2. Carga el autoloader de Composer del backend:
   - `require_once __DIR__ . '/../CineSampedro/vendor/autoload.php';`
3. Instancia un controlador de `CineSampedro/Controlador`.
4. Llama a un metodo concreto del controlador.

Eso significa que `Api/` no implementa logica de negocio: solo enruta hacia clases del backend.

## 3. Conexion API -> Backend (por endpoint)

## 3.1 Peliculas (listado)

- Endpoint de entrada: `Api/listar_peliculas.php`
- Delega a: `\App\Controlador\ApiPeliculaController::listar()`
- Flujo interno:
  1. `ApiPeliculaController` obtiene conexion con `BD::getConexion()`.
  2. Usa `PeliculaDAO->recuperaTodos()`.
  3. `PeliculaDAO` ejecuta SQL sobre tabla `peliculas`.
  4. El controlador serializa a JSON y responde.

## 3.2 Pelicula por id

- Endpoint de entrada: `Api/obtener_pelicula.php?id=...` (acepta tambien `id_pelicula`).
- Delega a: `\App\Controlador\ApiPeliculaController::obtener($id)`
- Flujo interno:
  1. `PeliculaDAO->recuperaPorId($id)`.
  2. Consulta extra de clasificacion (`clasificaciones_edad`) en el controlador.
  3. Respuesta JSON con datos de pelicula + clasificacion.

## 3.3 Sesiones por pelicula

- Endpoint de entrada: `Api/obtener_sesiones.php?id_pelicula=...`
- Delega a: `\App\Controlador\ApiSesionController::obtenerPorPelicula($id_pelicula)`
- Flujo interno:
  1. `SesionDAO->recuperarPorPelicula($id_pelicula)`.
  2. SQL con join `sesiones` + `salas`.
  3. El controlador transforma campos para Angular:
     - `id`, `fecha`, `hora`, `formato`, `precio`, `id_sala`.
  4. Devuelve array JSON listo para UI.

## 3.4 Mapa de asientos

- Endpoint de entrada: `Api/obtener_asientos.php?id_sesion=...&id_sala=...`
- Delega a: `\App\Controlador\ApiAsientoController::obtenerMapaAsientos($id_sala, $id_sesion)`
- Flujo interno:
  1. `AsientoDAO->recuperarEstadoAsientosPorSesion($id_sala, $id_sesion)`.
  2. SQL sobre `asientos` + `sesion_asientos` para estado real.
  3. El controlador adapta formato para frontend (`id`, `id_real_db`, `estado`, etc.).
  4. Respuesta JSON de la sala.

## 4. Conexion Frontend Angular -> carpeta API

Angular no llama directamente a `CineSampedro/`, llama a `/api`:

1. En `Sampedro_Cinema/src/app/services/cine.ts`:
   - `apiUrl = '/api'`
   - Requests:
     - `/api/listar_peliculas.php`
     - `/api/obtener_pelicula.php?id=...`
     - `/api/obtener_sesiones.php?id_pelicula=...`
     - `/api/obtener_asientos.php?id_sesion=...&id_sala=...`
2. En `Sampedro_Cinema/proxy.conf.json`:
   - Proxy `/api` -> `http://localhost:8000`
   - `pathRewrite`: `^/api` -> `/Api`
3. Resultado real en ejecucion:
   - Angular pide `/api/obtener_sesiones.php?...`
   - Dev server lo reescribe a `http://localhost:8000/Api/obtener_sesiones.php?...`

Por eso la carpeta `Api/` es la frontera real entre Angular y el backend.

## 5. Ciclo completo paso a paso (escenario real de usuario)

## 5.1 Carga de cartelera

1. Usuario abre Angular en `http://localhost:4200`.
2. `CarteleraComponent` ejecuta `cineService.getPeliculas()`.
3. Angular llama `/api/listar_peliculas.php`.
4. Proxy redirige a `http://localhost:8000/Api/listar_peliculas.php`.
5. `Api/listar_peliculas.php` carga autoload e invoca `ApiPeliculaController->listar()`.
6. Controlador -> `PeliculaDAO->recuperaTodos()` -> MySQL (`bd_cinema`).
7. JSON vuelve por el mismo camino hasta Angular.
8. `CarteleraComponent` pinta tarjetas de peliculas.

## 5.2 Usuario entra en una pelicula

1. Ruta de detalle/reserva recibe `id`.
2. `ReservaEntradasComponent` hace `forkJoin` de:
   - `getPelicula(id)`
   - `getSesiones(id)`
3. Angular llama ambos endpoints en `/api/...`.
4. Proxy los manda a `Api/obtener_pelicula.php` y `Api/obtener_sesiones.php`.
5. Scripts `Api/` delegan a controladores correspondientes.
6. DAOs consultan `peliculas`, `clasificaciones_edad`, `sesiones`, `salas`.
7. Angular recibe pelicula + sesiones y muestra opciones.

## 5.3 Usuario selecciona sesion

1. `ReservaEntradasComponent.alSeleccionarSesion(...)` toma `id_sesion` e `id_sala`.
2. Llama `cineService.getAsientos(idSesion, idSala)`.
3. Proxy redirige a `Api/obtener_asientos.php`.
4. `ApiAsientoController->obtenerMapaAsientos(...)` consulta estado real de butacas.
5. Angular pinta mapa (`Libre`, `Reservado`, `Ocupado`).
6. Usuario selecciona asientos (cambio visual en frontend).

## 6. Conexion a base de datos (detalle)

- Archivo: `CineSampedro/Modelo/Conexion/BD.php`
- Configuracion actual:
  - host: `127.0.0.1`
  - bd: `bd_cinema`
  - user: `root`
  - password: vacia
- Todos los controladores API de CineSampedro usan esta misma conexion PDO singleton.

## 7. Diferencia importante: `Api/` vs `CineSampedro/public/api/`

Existen dos superficies de API en el repo:

- `Api/`: endpoints simples usados por Angular actual (a traves de proxy).
- `CineSampedro/public/api/`: endpoints REST por recurso (GET/POST/PUT/DELETE), mas orientados a Postman/admin.

En el frontend actual, la integracion principal pasa por `Api/`, no por `CineSampedro/public/api/`.

## 8. Requisitos para que el ciclo funcione

1. MySQL con `bd_cinema` importada.
2. Servidor PHP levantado en la raiz del repo:
   - `php -S localhost:8000 -t .`
3. Angular levantado en `Sampedro_Cinema`:
   - `npm install`
   - `npm start`
4. Mantener encendidos ambos procesos a la vez.

## 9. Resumen visual del flujo

```text
Angular (4200)
  -> /api/*.php
    -> Proxy Angular (pathRewrite /api -> /Api)
      -> PHP server (8000) carpeta Api/
        -> require vendor/autoload de CineSampedro
          -> Controlador (CineSampedro/Controlador)
            -> DAO (CineSampedro/Modelo/DAO)
              -> MySQL (bd_cinema)
            <- JSON
          <- JSON
      <- JSON
  <- Render en componentes (Cartelera / ReservaEntradas)
```
