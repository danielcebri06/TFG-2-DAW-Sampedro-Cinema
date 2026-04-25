# Guia rapida: BD -> PHP -> API -> Angular

Esta guia te deja el primer ciclo funcionando y verificable.

## 1) Cargar la nueva base de datos `bd_cinema`

1. Abre MySQL (phpMyAdmin, MySQL Workbench o HeidiSQL).
2. Importa el archivo `bd_cinema.sql`.
3. Verifica que exista el esquema `bd_cinema` y las tablas.

Nota: en el backend ya quedo configurado `bd_cinema` en `CineSampedro/Modelo/Conexion/BD.php`.

## 2) Donde meter datos de peliculas

La forma correcta es en MySQL (no en archivos JSON del frontend).

Puedes insertar con SQL directamente. Ejemplo minimo:

```sql
USE bd_cinema;

INSERT INTO clasificaciones_edad (nombre, descripcion)
VALUES ('+13', 'Mayores de 13');

INSERT INTO peliculas (
  titulo,
  sinopsis,
  duracion_minutos,
  imagen,
  fecha_estreno,
  id_clasificacion
) VALUES (
  'Dune: Parte Dos',
  'Paul Atreides se une a los Fremen para buscar justicia.',
  166,
  'assets/dune2.jpg',
  '2024-03-01',
  1
);

INSERT INTO salas (numero, capacidad) VALUES (1, 120);

INSERT INTO sesiones (fecha_hora, precio, id_pelicula, id_sala)
VALUES ('2026-04-25 20:00:00', 8.50, 1, 1);

INSERT INTO asientos (fila, numero, tipo, id_sala)
VALUES (1, 1, 'normal', 1), (1, 2, 'normal', 1), (1, 3, 'PMR', 1);
```

## 3) Levantar API PHP para pruebas

Desde la raiz del repo (`TFG-2-DAW-Sampedro-Cinema`):

```powershell
php -S localhost:8000 -t .
```

Esto expone tus endpoints en:
- `http://localhost:8000/Api/...`
- `http://localhost:8000/CineSampedro/public/api/...`

Importante: usa siempre el mismo host (`localhost`) en backend, Postman y proxy de Angular para evitar conflictos IPv4/IPv6.

## 4) Probar en Postman (JSON)

### Peliculas

- Listar:
  - Metodo: `GET`
  - URL: `http://localhost:8000/Api/listar_peliculas.php`
- Obtener 1 pelicula:
  - Metodo: `GET`
  - URL: `http://localhost:8000/Api/obtener_pelicula.php?id=1`

### Sesiones por pelicula

- Metodo: `GET`
- URL: `http://localhost:8000/Api/obtener_sesiones.php?id_pelicula=1`

### Asientos por sesion y sala

- Metodo: `GET`
- URL: `http://localhost:8000/Api/obtener_asientos.php?id_sesion=1&id_sala=1`

Si no hay datos cargados, es normal recibir `[]`.

## 5) Levantar Angular conectado al backend

Desde `Sampedro_Cinema`:

```powershell
npm install
npm start
```

Quedo configurado proxy en `Sampedro_Cinema/proxy.conf.json` y servicio Angular en `/api`, por eso Angular enviara:
- `/api/listar_peliculas.php`
- `/api/obtener_pelicula.php?id=...`

El dev server redirige eso automaticamente a `http://localhost:8000/Api`.

Importante: para que funcione, el servidor PHP debe estar encendido al mismo tiempo.

## 6) Checklist de primer ciclo cerrado

- [ ] `bd_cinema` importada y con datos.
- [ ] `GET /Api/listar_peliculas.php` devuelve JSON en Postman.
- [ ] Angular muestra cartelera.
- [ ] Al abrir una pelicula, carga sesiones.
- [ ] Al elegir sesion, carga mapa de asientos.

## 7) Si algo falla

1. Error `SQLSTATE` o tabla/columna no existe:
   - Reimporta `bd_cinema.sql`.
   - Verifica que el backend apunte a `bd_cinema`.
2. Error `ECONNREFUSED` en Angular:
   - Arranca el backend con `php -S localhost:8000 -t .`.
   - No mezcles `localhost` y `127.0.0.1` entre backend/proxy/Postman: en algunos equipos `localhost` resuelve a IPv6 (`::1`) y `127.0.0.1` es IPv4, y si no coinciden aparece `ECONNREFUSED`.
3. Angular no trae API:
   - Verifica `proxy.conf.json` y reinicia `npm start`.
4. Respuesta vacia:
   - Carga datos en MySQL (no en Angular).
