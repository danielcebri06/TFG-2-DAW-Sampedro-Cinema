import { Component, OnInit } from '@angular/core';
import { CineService } from '../services/cine';

@Component({
  selector: 'app-cartelera',
  standalone: false,
  templateUrl: './cartelera.html',
  styleUrls: ['./cartelera.css'] // Asegúrate de que el nombre de tu CSS coincida
})
export class CarteleraComponent implements OnInit {
  
  // 1. Declaramos la variable global del componente
  peliculas: any[] = []; 

  constructor(private cineService: CineService) {}

  ngOnInit(): void {
    this.cineService.getPeliculas().subscribe({
      next: (datos) => {
        this.peliculas = datos; 
        console.log('Datos guardados en this.peliculas:', this.peliculas);
      },
      error: (error) => {
        console.error('Error al traer películas:', error);
      }
    });
  }
}