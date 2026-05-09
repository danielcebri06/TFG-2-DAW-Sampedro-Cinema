import { ChangeDetectorRef, Component, OnInit } from '@angular/core';
import { Admin } from '../../services/admin';

@Component({
  selector: 'app-admin-peliculas',
  standalone: false,
  templateUrl: './admin-peliculas.html',
  styleUrl: './admin-peliculas.css',
})
export class AdminPeliculas implements OnInit {
  peliculas: any[] = [];
  cargando: boolean = true;
  error: string = '';

  constructor(
    private adminService: Admin,
    private cd: ChangeDetectorRef
  ) {}

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

        this.cd.detectChanges();
      },
      error: (error) => {
        console.error('Error al cargar películas:', error);

        this.error = 'No se pudieron cargar las películas.';
        this.cargando = false;

        this.cd.detectChanges();
      }
    });
  }

  eliminarPelicula(id: number): void {
    const confirmar = confirm('¿Seguro que quieres eliminar esta película?');

    if (!confirmar) {
      return;
    }

    this.adminService.eliminarPelicula(id).subscribe({
      next: () => {
        this.cargarPeliculas();
      },
      error: (error) => {
        console.error('Error al eliminar película:', error);
        this.error = 'No se pudo eliminar la película.';
        this.cd.detectChanges();
      }
    });
  }
  
}

