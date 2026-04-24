import { Component, OnInit, ChangeDetectorRef } from '@angular/core';
import { CineService } from '../services/cine';

@Component({
  selector: 'app-cartelera',
  standalone: false,
  templateUrl: './cartelera.html',
  styleUrls: ['./cartelera.css'] // Asegúrate de que el nombre de tu CSS coincida
})
export class CarteleraComponent implements OnInit {
  peliculas: any[] = [];
  cargando = true;
  error = '';

  constructor(private cineService: CineService, private cdr: ChangeDetectorRef) {}

  ngOnInit(): void {
    this.cineService.getPeliculas().subscribe({
      next: (datos) => {
        this.peliculas = datos;
        this.cargando = false;
        this.cdr.detectChanges();
      },
      error: (err) => {
        console.error('Error al traer películas:', err);
        this.error = 'No se pudo conectar con el servidor. ¿Está el backend corriendo?';
        this.cargando = false;
        this.cdr.detectChanges();
      }
    });
  }
}