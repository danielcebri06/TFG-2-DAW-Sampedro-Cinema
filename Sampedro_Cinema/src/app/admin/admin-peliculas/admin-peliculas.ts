import { Component, OnInit } from '@angular/core';
import { Admin } from '../../services/admin';

@Component({
  selector: 'app-admin-peliculas',
  standalone: false,
  templateUrl: './admin-peliculas.html',
  styleUrl: './admin-peliculas.css',
})

export class AdminPeliculas implements OnInit{
  peliculas: any[] = [];
  cargando: boolean = true;
  error: string = '';

  constructor(private adminService: Admin) {}

  ngOnInit(): void {
    this.cargarPeliculas();
  }

  cargarPeliculas(): void {
    this.cargando = true;
    this.error = '';

    this.adminService.listarPeliculas().subscribe({
      next: (datos) => {
        this.peliculas = datos;
        this.cargando = false;
      },
      error: (error) => {
        console.error(error);
        this.error = 'No se pudieron cargar las peliculas.';
        this.cargando = false;
      }
    });
  }
}
