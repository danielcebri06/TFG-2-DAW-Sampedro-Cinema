# Flujo BD -> Backend -> API -> Frontend

## 1. Vista general (resumen rápido)

Tu app sigue esta cadena:

1. Frontend Angular hace una petición HTTP.
2. Esa petición llega a un endpoint PHP de la carpeta Api.
3. El endpoint llama a un controlador dentro de CineSampedro.
4. El controlador usa DAO + conexión BD para consultar MySQL (bd_cinema).
5. Se devuelve JSON.
6. Angular recibe JSON y pinta HTML.

---

## 2. Flujo principal: Cartelera

### Endpoint
GET /Api/listar_peliculas.php

### Ruta de ejecución interna
1. Api/listar_peliculas.php carga autoload y llama a ApiPeliculaController->listar().
2. ApiPeliculaController pide datos a PeliculaDAO.
3. PeliculaDAO consulta tabla peliculas en bd_cinema.
4. El controlador transforma entidades a JSON (id, titulo, sinopsis, duracion, imagen, etc.).
5. Angular (componente cartelera) recibe array de películas y renderiza cards.

### Qué recibe Angular
Un array JSON, por ejemplo:
- id_pelicula
- titulo
- sinopsis
- duracion_min
- imagen
- id_clasificacion

---

## 3. Flujo principal: Obtener película (detalle)

### Endpoint
GET /Api/obtener_pelicula.php?id=1
(también acepta id_pelicula)

### Ruta de ejecución interna
1. Api/obtener_pelicula.php valida parámetro id o id_pelicula.
2. Llama a ApiPeliculaController->obtener(id).
3. El controlador consulta PeliculaDAO->recuperaPorId(id).
4. Además consulta clasificaciones_edad para obtener nombre de clasificación.
5. Devuelve JSON de una película.

### Qué recibe Angular en reservar/:id
Un objeto JSON, por ejemplo:
- id_pelicula
- titulo
- sinopsis
- duracion_min
- imagen
- id_clasificacion
- clasificacion (texto, por ejemplo +16)

Con eso, reserva-entradas pinta:
- Póster dinámico
- Título
- Clasificación legible
- Duración
- Sinopsis

---

## 4. Cómo se encadenan sesiones y asientos (Postman)

Estos endpoints dependen del resultado anterior (detalle):

### A) Sesiones de una película
GET /Api/obtener_sesiones.php?id_pelicula=1

Devuelve lista de sesiones con:
- id (id_sesion)
- fecha
- hora
- formato (Sala X)
- precio
- id_sala

Uso práctico:
- Tomas id_sesion e id_sala de una sesión concreta para la siguiente llamada.

### B) Asientos de una sesión
GET /Api/obtener_asientos.php?id_sesion=5&id_sala=1

Devuelve mapa de asientos con estado:
- id (visual: Fila/Número)
- id_real_db
- fila
- numero
- tipo
- estado (Libre, Reservado, Ocupado)

Uso práctico:
- El frontend pinta el mapa y permite seleccionar asientos según estado.

---

## 5. Guía didáctica de pruebas en Postman

Orden recomendado para validar ciclo completo:

1. Cartelera
- GET http://127.0.0.1:8000/Api/listar_peliculas.php
- Elige un id_pelicula de la respuesta.

2. Detalle
- GET http://127.0.0.1:8000/Api/obtener_pelicula.php?id={id_pelicula}
- Verifica que venga imagen y clasificacion.

3. Sesiones
- GET http://127.0.0.1:8000/Api/obtener_sesiones.php?id_pelicula={id_pelicula}
- Elige una sesión y guarda id + id_sala.

4. Asientos
- GET http://127.0.0.1:8000/Api/obtener_asientos.php?id_sesion={id}&id_sala={id_sala}
- Verifica estados de asientos.

Si este flujo funciona en Postman, el backend está bien. Si falla en pantalla pero no en Postman, el problema está en renderizado/ruteo/frontend.

---

## 6. ¿Para qué sirve CineSampedro/public si ya existe Api afuera?

Ambas carpetas exponen API, pero con filosofías distintas:

- Carpeta Api (raíz):
  - Endpoints directos tipo archivo PHP por acción.
  - Es la ruta que estás consumiendo hoy desde Angular.
  - Muy útil para desarrollo rápido y pruebas.

- Carpeta CineSampedro/public:
  - Es la raíz pública típica de una app backend estructurada.
  - Ahí suelen ir los endpoints de producción y entrada web pública.
  - Evita exponer archivos internos de la app (controladores, modelo, etc.).

En resumen:
- Hoy usas activamente Api/.
- public/ es la estructura recomendada para despliegue limpio y seguro en servidor real.
- Lo ideal a futuro es unificar en una sola entrada para evitar duplicidad de rutas.
