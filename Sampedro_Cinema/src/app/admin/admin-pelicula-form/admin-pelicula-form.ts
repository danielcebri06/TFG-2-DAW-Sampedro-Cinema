import { ChangeDetectorRef, Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Admin } from '../../services/admin';

@Component({
  selector: 'app-admin-pelicula-form',
  standalone: false,
  templateUrl: './admin-pelicula-form.html',
  styleUrl: './admin-pelicula-form.css'
})
export class AdminPeliculaForm implements OnInit {

  pelicula: any = {
    titulo: '',
    sinopsis: '',
    duracion_minutos: null,
    fecha_estreno: '',
    imagen: '',
    id_clasificacion: null
  };

  modoEditar = false;
  idPelicula: number | null = null;

  cargando = false;
  guardando = false;
  error = '';
  mensaje = '';

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private adminService: Admin,
    private cd: ChangeDetectorRef
  ) {}

  ngOnInit(): void {
    const id = this.route.snapshot.paramMap.get('id');

    if (id) {
      this.modoEditar = true;
      this.idPelicula = Number(id);
      this.cargarPelicula(this.idPelicula);
    }
  }

  cargarPelicula(id: number): void {
    this.cargando = true;
    this.error = '';

    this.adminService.obtenerPelicula(id).subscribe({
      next: (datos) => {
        this.pelicula = {
          titulo: datos.titulo,
          sinopsis: datos.sinopsis,
          duracion_minutos: datos.duracion_minutos || datos.duracion_min,
          fecha_estreno: datos.fecha_estreno,
          imagen: datos.imagen,
          id_clasificacion: datos.id_clasificacion
        };

        this.cargando = false;

        this.cd.detectChanges();
      },
      error: () => {
        this.error = 'No se pudo cargar la película.';
        this.cargando = false;
        this.cd.detectChanges();
      }
    });
  }

  guardarPelicula(): void {
    this.guardando = true;
    this.error = '';
    this.mensaje = '';

    if (this.modoEditar && this.idPelicula !== null) {
      this.adminService.modificarPelicula(this.idPelicula, this.pelicula).subscribe({
        next: () => {
          this.mensaje = 'Película modificada correctamente.';
          this.guardando = false;
          this.router.navigate(['/admin/peliculas']);
        },
        error: () => {
          this.error = 'No se pudo modificar la película.';
          this.guardando = false;
        }
      });
    } else {
      this.adminService.crearPelicula(this.pelicula).subscribe({
        next: () => {
          this.mensaje = 'Película creada correctamente.';
          this.guardando = false;
          this.router.navigate(['/admin/peliculas']);
        },
        error: () => {
          this.error = 'No se pudo crear la película.';
          this.guardando = false;
        }
      });
    }
  }
}