import { ChangeDetectorRef, Component, OnInit } from '@angular/core';
import { Admin } from '../../services/admin';

@Component({
  selector: 'app-admin-sesiones',
  standalone: false,
  templateUrl: './admin-sesiones.html',
  styleUrl: './admin-sesiones.css',
})
export class AdminSesiones implements OnInit {

  sesiones: any[] = [];
  peliculas: any[] = [];
  salas: any[] = [];

  cargando: boolean = true;
  error: string = '';

  constructor(
    private adminService: Admin,
    private cd: ChangeDetectorRef
  ) {}

  ngOnInit(): void {
    this.cargarPeliculas();
    this.cargarSalas();
    this.cargarSesiones();
  }

  cargarSesiones(): void {
    this.cargando = true;
    this.error = '';

    this.adminService.listarSesiones().subscribe({
      next: (datos) => {
        this.sesiones = datos;
        this.cargando = false;
        this.cd.detectChanges();
      },
      error: (error) => {
        console.error('Error al cargar sesiones:', error);
        this.error = 'No se pudieron cargar las sesiones.';
        this.cargando = false;
        this.cd.detectChanges();
      }
    });
  }

  cargarPeliculas(): void {
    this.adminService.listarPeliculas().subscribe({
      next: (datos) => {
        this.peliculas = datos;
        this.cd.detectChanges();
      },
      error: (error) => {
        console.error('Error al cargar películas:', error);
      }
    });
  }

  cargarSalas(): void {
    this.adminService.listarSalas().subscribe({
      next: (datos) => {
        this.salas = datos;
        this.cd.detectChanges();
      },
      error: (error) => {
        console.error('Error al cargar salas:', error);
      }
    });
  }

  obtenerTituloPelicula(id_pelicula: number): string {
    const pelicula = this.peliculas.find(p => p.id_pelicula === id_pelicula);

    if (!pelicula) {
      return 'Película no encontrada';
    }

    return pelicula.titulo;
  }

  obtenerNombreSala(id_sala: number): string {
    const sala = this.salas.find(s => s.id_sala === id_sala);

    if (!sala) {
      return 'Sala no encontrada';
    }

    return `Sala ${sala.numero}`;
  }

  eliminarSesion(id: number): void {
    const confirmar = confirm('¿Seguro que quieres eliminar esta sesión?');

    if (!confirmar) {
      return;
    }

    this.adminService.eliminarSesion(id).subscribe({
      next: () => {
        this.cargarSesiones();
      },
      error: (error) => {
        console.error('Error al eliminar sesión:', error);
        this.error = 'No se pudo eliminar la sesión.';
        this.cd.detectChanges();
      }
    });
  }
}
