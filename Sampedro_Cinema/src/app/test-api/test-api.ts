import { Component, OnInit, ChangeDetectorRef } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-test-api',
  standalone: false,
  template: `
    <div style="padding: 20px; font-family: monospace;">
      <h2>Test ciclo API → Angular</h2>

      <h3>Estado</h3>
      <p>{{ estado }}</p>

      <h3>Películas recibidas ({{ peliculas.length }})</h3>
      <div *ngFor="let p of peliculas" style="border:1px solid #ccc; margin:8px 0; padding:8px; display:flex; gap:12px; align-items:center;">
        <img [src]="'/IMAGENES/' + p.imagen" style="width:60px; height:90px; object-fit:cover;" onerror="this.onerror=null; this.style.background='#aaa'; this.style.display='block'">
        <div>
          <strong>{{ p.id_pelicula }} - {{ p.titulo }}</strong><br>
          Imagen en BD: <code>{{ p.imagen }}</code><br>
          Duración: {{ p.duracion_min }} min
        </div>
      </div>

      <div *ngIf="error" style="color:red; margin-top:12px;">
        <strong>Error:</strong> {{ error }}
      </div>
    </div>
  `
})
export class TestApiComponent implements OnInit {
  peliculas: any[] = [];
  estado = 'Cargando...';
  error = '';

  constructor(private http: HttpClient, private cdr: ChangeDetectorRef) {}

  ngOnInit(): void {
    this.http.get<any[]>('/api/listar_peliculas.php').subscribe({
      next: (data) => {
        this.peliculas = data;
        this.estado = `OK — ${data.length} películas recibidas`;
        this.cdr.detectChanges();
      },
      error: (err) => {
        this.estado = 'FALLO';
        this.error = err.message || JSON.stringify(err);
        this.cdr.detectChanges();
      }
    });
  }
}
