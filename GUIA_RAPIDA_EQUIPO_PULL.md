# Guia rapida para el equipo (despues de hacer pull)

Objetivo: levantar BD + backend PHP + frontend Angular sin errores de proxy.

## 1) Requisitos minimos

- XAMPP (Apache + MySQL).
- PHP disponible en terminal (`php -v`).
- Node.js y npm (`node -v`, `npm -v`).

## 2) Arranque recomendado (orden exacto)

1. Iniciar MySQL desde XAMPP.
2. Importar `bd_cinema.sql` en MySQL (solo la primera vez o cuando cambie la BD).
3. Desde la raiz del repo, arrancar API en puerto 8000:

```powershell
php -S localhost:8000 -t .
```

4. En otra terminal, entrar en `Sampedro_Cinema` e instalar dependencias (si hace falta):

```powershell
npm install
```

5. Levantar Angular:

```powershell
ng serve -o
```

## 3) Verificacion en 30 segundos

1. Probar backend directo:
   - `http://localhost:8000/Api/listar_peliculas.php`
   - Debe devolver JSON (array de peliculas).
2. Probar proxy de Angular:
   - `http://localhost:4200/api/listar_peliculas.php`
   - Debe devolver el mismo JSON.

## 4) Regla clave de red (evita el error mas comun)

Usar siempre `localhost` en todos lados:
- backend (`php -S localhost:8000 -t .`)
- Postman (`http://localhost:8000/...`)
- proxy Angular (`target: http://localhost:8000`)

No mezclar `localhost` y `127.0.0.1`, porque en algunos Windows `localhost` puede resolver a IPv6 (`::1`) y `127.0.0.1` es IPv4. Si backend y proxy quedan en familias distintas, aparece `ECONNREFUSED`.

## 5) Errores tipicos y solucion

### Error: `ECONNREFUSED 127.0.0.1:8000`

- El backend no esta levantado, o esta levantado en `localhost` y el proxy apunta a `127.0.0.1`.
- Solucion: arrancar backend con `localhost` y reiniciar `ng serve`.

### Error: `500 Internal Server Error` al pedir `/api/...`

- El proxy no logra llegar al backend y Angular muestra 500.
- Solucion: comprobar primero `http://localhost:8000/Api/listar_peliculas.php`.

### Cambie `proxy.conf.json` y no se aplica

- `ng serve` no siempre recarga cambios de proxy en caliente.
- Solucion: parar y volver a ejecutar `ng serve`.

## 6) Comandos utiles de diagnostico

```powershell
# Ver si hay algo escuchando en el 8000
Get-NetTCPConnection -LocalPort 8000 -State Listen

# Verificar backend
Invoke-WebRequest http://localhost:8000/Api/listar_peliculas.php

# Verificar frontend+proxy
Invoke-WebRequest http://localhost:4200/api/listar_peliculas.php
```

## 7) Resumen corto para el equipo

Si haces pull y deja de cargar peliculas:
1. MySQL arriba.
2. `php -S localhost:8000 -t .` desde la raiz del repo.
3. `ng serve -o` en `Sampedro_Cinema`.
4. Usar siempre `localhost` (no mezclar con `127.0.0.1`).
