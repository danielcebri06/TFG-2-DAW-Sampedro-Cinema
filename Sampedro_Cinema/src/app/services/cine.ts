import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({ providedIn: 'root' })
export class CineService {
  private apiUrl = 'http://localhost/TFG-2-DAW-Sampedro-Cinema/Api';

  constructor(private http: HttpClient) { }

  getPeliculas(): Observable<any[]> {
    return this.http.get<any[]>(`${this.apiUrl}/listar_peliculas.php`);
  }
  
  getPelicula(id: number): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}/obtener_pelicula.php?id=${id}`);
  }

  getSesiones(idPelicula: number): Observable<any[]> {
    return this.http.get<any[]>(`${this.apiUrl}/obtener_sesiones.php?id_pelicula=${idPelicula}`);
  }

  getAsientos(idSesion: number, idSala: number): Observable<any[]> {
    return this.http.get<any[]>(`${this.apiUrl}/obtener_asientos.php?id_sesion=${idSesion}&id_sala=${idSala}`);
  }
}