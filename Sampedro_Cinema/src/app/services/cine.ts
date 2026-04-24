import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { timeout } from 'rxjs';

const API_TIMEOUT_MS = 10000;

@Injectable({ providedIn: 'root' })
export class CineService {
  private apiUrl = '/api';

  constructor(private http: HttpClient) { }

  getPeliculas(): Observable<any[]> {
    return this.http.get<any[]>(`${this.apiUrl}/listar_peliculas.php`).pipe(timeout(API_TIMEOUT_MS));
  }
  
  getPelicula(id: number): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}/obtener_pelicula.php?id=${id}`).pipe(timeout(API_TIMEOUT_MS));
  }

  getSesiones(idPelicula: number): Observable<any[]> {
    return this.http.get<any[]>(`${this.apiUrl}/obtener_sesiones.php?id_pelicula=${idPelicula}`).pipe(timeout(API_TIMEOUT_MS));
  }

  getAsientos(idSesion: number, idSala: number): Observable<any[]> {
    return this.http.get<any[]>(`${this.apiUrl}/obtener_asientos.php?id_sesion=${idSesion}&id_sala=${idSala}`).pipe(timeout(API_TIMEOUT_MS));
  }
}