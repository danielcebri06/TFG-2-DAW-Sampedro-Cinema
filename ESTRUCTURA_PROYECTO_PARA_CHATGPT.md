# Estructura principal del proyecto (para compartir con ChatGPT)

Este documento resume las partes clave del proyecto y que contiene cada una.

## Vista general

```text
TFG-2-DAW-Sampedro-Cinema/
├─ Api/
├─ CineSampedro/
├─ IMAGENES/
├─ Sampedro_Cinema/
├─ bd_cinema.sql
├─ package.json
├─ GUIA_POSTMAN_BD_BACKEND_FRONTEND.md
├─ GUIA_RAPIDA_EQUIPO_PULL.md
└─ CICLO_API_BACKEND_FRONTEND.md
```

## 1) Carpeta Api/ (puente simple hacia backend)

```text
Api/
├─ listar_peliculas.php
├─ obtener_pelicula.php
├─ obtener_sesiones.php
└─ obtener_asientos.php
```

Que hace:
- Son endpoints PHP simples consumidos por Angular.
- Cargan `CineSampedro/vendor/autoload.php`.
- Instancian controladores del backend (`CineSampedro/Controlador`).
- Devuelven JSON para frontend.

Archivos clave:
- `listar_peliculas.php`: lista peliculas.
- `obtener_pelicula.php`: detalle de una pelicula por ID.
- `obtener_sesiones.php`: sesiones de una pelicula.
- `obtener_asientos.php`: mapa/estado de asientos por sala y sesion.

## 2) Carpeta CineSampedro/ (backend PHP principal)

```text
CineSampedro/
├─ composer.json
├─ composer.lock
├─ index.php
├─ Controlador/
│  ├─ ApiAdminController.php
│  ├─ ApiAsientoController.php
│  ├─ ApiPagoController.php
│  ├─ ApiPeliculaController.php
│  ├─ ApiReservaController.php
│  ├─ ApiSesionController.php
│  └─ ApiUsuarioController.php
├─ Modelo/
│  ├─ Conexion/
│  │  └─ BD.php
│  ├─ DAO/
│  │  ├─ AsientoDAO.php
│  │  ├─ PagoDAO.php
│  │  ├─ PeliculaDAO.php
│  │  ├─ ReservaAsientoDAO.php
│  │  ├─ ReservaDAO.php
│  │  ├─ SalaDAO.php
│  │  ├─ SesionDAO.php
│  │  └─ UsuarioDAO.php
│  └─ Entidades/
│     ├─ Asiento.php
│     ├─ Pago.php
│     ├─ Pelicula.php
│     ├─ Reserva.php
│     ├─ ReservaAsiento.php
│     ├─ Sala.php
│     ├─ Sesion.php
│     └─ Usuario.php
├─ public/
│  └─ api/
│     ├─ admin.php
│     ├─ pago.php
│     ├─ pelicula.php
│     ├─ reserva.php
│     ├─ sesion.php
│     └─ usuario.php
├─ nbproject/
└─ vendor/
```

Que hace:
- Es el backend de negocio completo en PHP.
- Arquitectura por capas:
  - Controlador: recibe peticiones y responde JSON.
  - DAO: consultas SQL y acceso a datos.
  - Entidades: modelos de dominio.
  - Conexion (`BD.php`): conexion PDO a MySQL (`bd_cinema`).

Puntos importantes:
- `composer.json` define autoload PSR-4 para namespace `App\\`.
- `vendor/` contiene autoload generado por Composer.
- `public/api/` expone endpoints REST por recurso (admin, pelicula, sesion, etc.).

## 3) Carpeta IMAGENES/ (recursos multimedia)

```text
IMAGENES/
├─ ALTAS_CAPACIDADES.png
├─ AMARGA_NAVIDAD.png
├─ HOPPERS.png
├─ LAPONIA.png
├─ NOCHE_dE_BODAS_2.png
├─ NO_tE_OLVIDARE.png
├─ SUPER_MARIO.png
├─ TORRENTE_PRESIDENTE.png
└─ PROXIMOS EXTRENOS/
```

Que hace:
- Almacena imagenes de peliculas y posibles assets promocionales.
- Se usa como repositorio de contenido visual para frontend/backend.

## 4) Carpeta Sampedro_Cinema/ (frontend Angular)

```text
Sampedro_Cinema/
├─ angular.json
├─ package.json
├─ package-lock.json
├─ proxy.conf.json
├─ README.md
├─ tsconfig.json
├─ tsconfig.app.json
├─ tsconfig.spec.json
├─ public/
├─ src/
│  ├─ index.html
│  ├─ main.ts
│  ├─ material-theme.scss
│  ├─ styles.css
│  └─ app/
│     ├─ app-module.ts
│     ├─ app-routing-module.ts
│     ├─ app.ts
│     ├─ app.html
│     ├─ app.css
│     ├─ app.spec.ts
│     ├─ cartelera/
│     │  ├─ cartelera.ts
│     │  ├─ cartelera.html
│     │  ├─ cartelera.css
│     │  └─ cartelera.spec.ts
│     ├─ reserva-entradas/
│     │  ├─ reserva-entradas.ts
│     │  ├─ reserva-entradas.html
│     │  ├─ reserva-entradas.css
│     │  └─ reserva-entradas.spec.ts
│     ├─ services/
│     │  ├─ cine.ts
│     │  └─ cine.spec.ts
│     └─ test-api/
│        └─ test-api.ts
├─ .angular/
├─ .vscode/
└─ node_modules/
```

Que hace:
- Es la aplicacion cliente (UI) en Angular.
- Componentes principales:
  - `cartelera`: listado de peliculas.
  - `reserva-entradas`: detalle, sesiones y seleccion de asientos.
- Servicio principal:
  - `src/app/services/cine.ts` centraliza llamadas HTTP.
- Conexion API en desarrollo:
  - `proxy.conf.json` redirige `/api` hacia `http://localhost:8000/Api`.

## 5) Archivos raiz relevantes

- `bd_cinema.sql`: esquema y datos base de MySQL.
- `package.json` (raiz): configuracion npm del workspace global.
- `GUIA_POSTMAN_BD_BACKEND_FRONTEND.md`: guia de pruebas y conexion E2E.
- `GUIA_RAPIDA_EQUIPO_PULL.md`: guia de trabajo en equipo (pull/sincronizacion).
- `CICLO_API_BACKEND_FRONTEND.md`: explicacion detallada del flujo Angular -> Api -> CineSampedro -> BD.

## 6) Resumen corto de arquitectura

```text
Angular (Sampedro_Cinema)
  -> /api/*.php (proxy)
    -> carpeta Api/ (wrappers)
      -> controladores de CineSampedro/
        -> DAO + Entidades + BD.php
          -> MySQL (bd_cinema)
```

Con este mapa, ChatGPT puede entender rapidamente como esta organizado el proyecto, donde esta cada responsabilidad y por donde viajan los datos.
