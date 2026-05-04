import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';


@Injectable({
  providedIn: 'root',
})

export class Admin {
  private apiUrl = '/api';

  constructor(private http: HttpClient){}

  listarPeliculas(): Observable<any[]> {
    return this.http.get<any[]>(`${this.apiUrl}/listar_peliculas.php`);
  }
}
