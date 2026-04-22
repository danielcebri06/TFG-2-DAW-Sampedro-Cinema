import { Injectable } from '@angular/core';
import { Observable, of } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class CineService {
  // Base de datos simulada
  private peliculas = [
    { id: 1, titulo: 'EL CABALLERO OSCURO', duracion: '152 min', edad: '+12', generos: ['Acción', 'Drama'], sinopsis: 'Batman se enfrenta al Joker.' },
    { id: 2, titulo: 'DUNE: PARTE DOS', duracion: '166 min', edad: '+12', generos: ['Ciencia Ficción'], sinopsis: 'Paul Atreides se une a los Fremen.' },
    { id: 3, titulo: 'OPPENHEIMER', duracion: '180 min', edad: '+16', generos: ['Biografía', 'Historia'], sinopsis: 'La historia del creador de la bomba atómica.' },
    { id: 4, titulo: 'DEADPOOL & WOLVERINE', duracion: '127 min', edad: '+18', generos: ['Acción', 'Comedia'], sinopsis: 'Wolverine se recupera de sus heridas y se cruza con Deadpool.' }
  ];

  constructor() { }

  getPeliculas(): Observable<any[]> { return of(this.peliculas); }
  
  getPelicula(id: number): Observable<any> { return of(this.peliculas.find(p => p.id === id)); }

  getSesiones(idPelicula: number): Observable<any[]> {
    return of([
      { id: 101, fecha: 'Hoy', hora: '18:00', formato: '2D', precio: 8.50 },
      { id: 102, fecha: 'Hoy', hora: '21:30', formato: 'VOSE', precio: 8.50 },
      { id: 103, fecha: 'Mañana', hora: '19:00', formato: '3D', precio: 10.00 }
    ]);
  }

  getAsientos(idSesion: number): Observable<any[]> {
    const asientos = [];
    const filas = ['A', 'B', 'C', 'D', 'E'];
    for (let f = 0; f < filas.length; f++) {
      for (let n = 1; n <= 10; n++) {
        asientos.push({
          id: `${filas[f]}${n}`, fila: filas[f], numero: n,
          estado: Math.random() < 0.2 ? 'Ocupado' : 'Libre'
        });
      }
    }
    return of(asientos);
  }
}