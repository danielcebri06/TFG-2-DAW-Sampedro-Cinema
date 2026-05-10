import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';


@Injectable({
  providedIn: 'root',
})

export class Admin {
  private apiUrl = '/admin-api';

  constructor(private http: HttpClient){}

  //LISTA TODAS LAS PELICULAS
  listarPeliculas(): Observable<any[]> {
    return this.http.get<any[]>(`${this.apiUrl}/pelicula.php`);
  }

  //OBTIENE UNA PELICULA POR SU ID
  obtenerPelicula(id: number): Observable<any>{
    return this.http.get<any>(`${this.apiUrl}/pelicula.php?id_pelicula=${id}`);
  }

  //CREA UNA NUEVA PELICULA
  crearPelicula(pelicula: any): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}/pelicula.php`, pelicula);
  }

  //MODIFICA UNA PELICULA EXISTENTE
  modificarPelicula(id: number, pelicula: any): Observable<any> {
    return this.http.put<any>(`${this.apiUrl}/pelicula.php?id_pelicula=${id}`, pelicula);
  }

  //ELIMINA UNA PELICULA
  eliminarPelicula(id:number): Observable<any> {
    return this.http.delete<any>(`${this.apiUrl}/pelicula.php?id_pelicula=${id}`);
  }

  // LISTA TODAS LAS SESIONES
  listarSesiones(): Observable<any[]> {
    return this.http.get<any[]>(`${this.apiUrl}/sesion.php`);
  }

  // OBTIENE UNA SESIÓN POR SU ID
  obtenerSesion(id: number): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}/sesion.php?id_sesion=${id}`);
  }

  // CREA UNA NUEVA SESIÓN
  crearSesion(sesion: any): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}/sesion.php`, sesion);
  }

  // MODIFICA UNA SESIÓN EXISTENTE
  modificarSesion(id: number, sesion: any): Observable<any> {
    return this.http.put<any>(`${this.apiUrl}/sesion.php?id_sesion=${id}`, sesion);
  }

  // ELIMINA UNA SESIÓN
  eliminarSesion(id: number): Observable<any> {
    return this.http.delete<any>(`${this.apiUrl}/sesion.php?id_sesion=${id}`);
  }

  // LISTA TODAS LAS SALAS
  listarSalas(): Observable<any[]> {
    return this.http.get<any[]>(`${this.apiUrl}/admin.php?accion=salas`);
  }

  // OBTIENE UNA SALA POR SU ID
  obtenerSala(id: number): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}/admin.php?accion=salas&id_sala=${id}`);
  }

  // CREA UNA NUEVA SALA
  crearSala(sala: any): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}/admin.php?accion=salas`, sala);
  }

  // MODIFICA UNA SALA EXISTENTE
  modificarSala(id: number, sala: any): Observable<any> {
    return this.http.put<any>(`${this.apiUrl}/admin.php?accion=salas&id_sala=${id}`, sala);
  }

  // ELIMINA UNA SALA
  eliminarSala(id: number): Observable<any> {
    return this.http.delete<any>(`${this.apiUrl}/admin.php?accion=salas&id_sala=${id}`);
  }
}
